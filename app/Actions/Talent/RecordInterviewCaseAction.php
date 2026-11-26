<?php

namespace App\Actions\Talent;

use App\Enums\InterviewAttendance;
use App\Enums\InterviewStage;
use App\Enums\ReapplyEligibility;
use App\Enums\TalentDecisionOutcome;
use App\Enums\TalentDecisionReason;
use App\Enums\TalentNoteCategory;
use App\Models\Talent;
use App\Models\TalentNote;
use App\Models\TalentReview;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RecordInterviewCaseAction
{
    /**
     * Record a structured interview evaluation and full candidate case decision.
     * Additive and transaction-safe for existing live candidate records.
     */
    public function execute(Talent $talent, array $data, ?User $actor = null): TalentReview
    {
        $actorId = $actor?->id
            ?? Auth::id()
            ?? $talent->assigned_recruiter_id
            ?? $talent->created_by
            ?? User::query()->value('id');

        $outcome = filled($data['decision_outcome'] ?? null)
            ? TalentDecisionOutcome::tryFrom((string) $data['decision_outcome'])
            : null;

        $reason = filled($data['decision_reason'] ?? null)
            ? TalentDecisionReason::tryFrom((string) $data['decision_reason'])
            : null;

        $stage = filled($data['interview_stage'] ?? null)
            ? InterviewStage::tryFrom((string) $data['interview_stage'])
            : InterviewStage::GENERAL_REVIEW;

        $attendance = filled($data['attendance_status'] ?? null)
            ? InterviewAttendance::tryFrom((string) $data['attendance_status'])
            : InterviewAttendance::ATTENDED;

        $eligibility = filled($data['reapply_eligibility'] ?? null)
            ? ReapplyEligibility::tryFrom((string) $data['reapply_eligibility'])
            : null;

        $summary = trim((string) ($data['decision_summary'] ?? ''));
        $strengths = trim((string) ($data['strengths'] ?? ''));
        $weaknesses = trim((string) ($data['weaknesses'] ?? ''));
        $noticePeriod = trim((string) ($data['notice_period'] ?? ''));
        $rating = filled($data['rating'] ?? null) ? (int) $data['rating'] : null;
        $interviewSalary = filled($data['interview_salary_expectation'] ?? null)
            ? (float) $data['interview_salary_expectation']
            : null;

        $review = DB::transaction(function () use (
            $talent,
            $data,
            $actorId,
            $outcome,
            $reason,
            $stage,
            $attendance,
            $eligibility,
            $summary,
            $strengths,
            $weaknesses,
            $noticePeriod,
            $rating,
            $interviewSalary
        ): TalentReview {
            $review = TalentReview::create([
                'talent_id' => $talent->id,
                'reviewer_id' => $actorId,
                'reviewed_at' => now(),
                'interview_stage' => $stage?->value,
                'attendance_status' => $attendance?->value,
                'rating' => $rating,
                'recommendation' => $outcome?->value,
                'decision_reason' => $reason?->value,
                'strengths' => $strengths !== '' ? $strengths : null,
                'weaknesses' => $weaknesses !== '' ? $weaknesses : null,
                'interview_salary_expectation' => $interviewSalary,
                'notice_period' => $noticePeriod !== '' ? $noticePeriod : null,
                'notes' => $summary !== '' ? $summary : null,
            ]);

            $talentUpdates = [
                'decision_outcome' => $outcome?->value,
                'decision_reason' => $reason?->value,
                'decision_summary' => $summary !== '' ? $summary : $talent->decision_summary,
                'reapply_eligibility' => $eligibility?->value ?? $talent->reapply_eligibility?->value,
                'decision_by' => $actorId,
                'decision_at' => now(),
                'last_profile_review_at' => now(),
            ];

            if ($interviewSalary !== null) {
                $talentUpdates['interview_salary_expectation'] = $interviewSalary;
            }

            if (array_key_exists('recommended_job_posting_id', $data)) {
                $talentUpdates['recommended_job_posting_id'] = filled($data['recommended_job_posting_id'])
                    ? (int) $data['recommended_job_posting_id']
                    : null;
            }

            if (filled($data['status_id'] ?? null)) {
                $talentUpdates['status_id'] = (int) $data['status_id'];
            }

            $talent->update($talentUpdates);

            $noteLines = array_filter([
                'المرحلة: ' . ($stage?->getLabel() ?? 'غير محدد') . ' | الحضور: ' . ($attendance?->getLabel() ?? 'غير محدد'),
                $outcome ? 'القرار: ' . $outcome->getLabel() : null,
                $reason ? 'السبب: ' . $reason->getLabel() : null,
                $rating ? "التقييم: {$rating}/5" : null,
                $eligibility ? 'إعادة التقديم: ' . $eligibility->getLabel() : null,
                $strengths !== '' ? "نقاط القوة: {$strengths}" : null,
                $weaknesses !== '' ? "نقاط الضعف / التحفظات: {$weaknesses}" : null,
                $summary !== '' ? "ملخص الكيس:\n{$summary}" : null,
            ]);

            $noteCategory = ($outcome && $outcome->requiresReason())
                ? TalentNoteCategory::REJECTION
                : TalentNoteCategory::INTERVIEW;

            TalentNote::create([
                'talent_id' => $talent->id,
                'user_id' => $actorId,
                'category' => $noteCategory->value,
                'body' => implode("\n", $noteLines),
            ]);

            return $review;
        });

        if (! empty($data['send_rejection_email']) && ! empty($talent->email) && ! empty($data['rejection_email_body'])) {
            $subject = trim((string) ($data['rejection_email_subject'] ?? 'تحديث بخصوص طلب التوظيف'));
            $body = (string) $data['rejection_email_body'];

            try {
                Mail::raw($body, function ($message) use ($talent, $subject): void {
                    $message->to($talent->email)->subject($subject);
                });
            } catch (\Throwable $e) {
                Log::warning('فشل إرسال إيميل الاعتذار للمرشح: ' . $e->getMessage());
            }

            TalentNote::create([
                'talent_id' => $talent->id,
                'user_id' => $actorId,
                'category' => TalentNoteCategory::COMMUNICATION->value,
                'body' => "📧 تم إرسال إيميل تحديث/اعتذار: [{$subject}]\n{$body}",
            ]);
        }

        return $review;
    }
}
