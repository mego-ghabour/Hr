<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitJobApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $job = $this->route('job');
        $config = $job->primary_fields_config ?? [];
        $formSchema = $job->form_schema ?? [];

        $rules = [];

        // Helper to determine if a primary field is marked as required
        $isReq = function (string $field, bool $default = false) use ($config): string {
            return ($config["required_{$field}"] ?? $default) ? 'required' : 'nullable';
        };

        if ($config['show_full_name'] ?? true) {
            $rules['full_name'] = [$isReq('full_name', true), 'string', 'min:3', 'max:255'];
        }
        if ($config['show_email'] ?? true) {
            $rules['email'] = [
                $isReq('email', true), 
                'email:rfc,filter', 
                'max:255',
                Rule::unique('talents', 'email')->where(function ($query) use ($job) {
                    return $query->where('job_posting_id', $job->id);
                })
            ];
        }
        if ($config['show_phone'] ?? true) {
            $rules['phone'] = [$isReq('phone', true), 'string', 'regex:/^\+?[0-9][0-9\s\-\(\)]{7,20}$/', 'min:8', 'max:20'];
        }
        if ($config['show_national_id'] ?? false) {
            $rules['national_id'] = [$isReq('national_id', false), 'string', 'max:50'];
        }
        if ($config['show_city'] ?? false) {
            $rules['city'] = [$isReq('city', false), 'string', 'max:100'];
        }
        if ($config['show_birth_date'] ?? false) {
            $rules['birth_date'] = [$isReq('birth_date', false), 'date', 'before:today'];
        }
        if ($config['show_gender'] ?? false) {
            $rules['gender'] = [$isReq('gender', false), 'string', 'in:male,female,ذكر,أنثى'];
        }
        if ($config['show_linkedin'] ?? true) {
            $rules['linkedin_url'] = [$isReq('linkedin', false), 'url', 'max:255', 'regex:/^https:\/\/(www\.)?linkedin\.com\/.*$/i'];
        }
        if ($config['show_portfolio'] ?? true) {
            $rules['portfolio_url'] = [$isReq('portfolio', false), 'url', 'max:255'];
        }

        if ($config['show_resume'] ?? true) {
            $rules['resume'] = [$isReq('resume', true), 'file', 'extensions:pdf,doc,docx', 'max:10240'];
        }
        if ($config['show_current_job_title'] ?? true) {
            $rules['current_job_title'] = [$isReq('current_job_title', false), 'string', 'max:255'];
        }
        if ($config['show_current_company'] ?? true) {
            $rules['current_company'] = [$isReq('current_company', false), 'string', 'max:255'];
        }
        if ($config['show_years_of_experience'] ?? true) {
            $rules['years_of_experience'] = [$isReq('years_of_experience', false), 'numeric', 'min:0', 'max:50'];
        }
        if ($config['show_expected_salary'] ?? true) {
            $rules['expected_salary'] = [$isReq('expected_salary', false), 'numeric', 'min:0'];
        }
        if ($config['show_seniority_level'] ?? true) {
            $rules['seniority_level'] = [$isReq('seniority_level', false), 'string', 'max:100'];
        }
        if ($config['show_work_preference'] ?? true) {
            $rules['work_preference'] = [$isReq('work_preference', false), 'string', 'max:100'];
        }
        if ($config['show_availability'] ?? true) {
            $rules['availability'] = [$isReq('availability', false), 'string', 'max:100'];
        }
        if ($config['show_general_notes'] ?? true) {
            $rules['general_notes'] = [$isReq('general_notes', false), 'string', 'max:2000'];
        }

        // Dynamic Rules based on Form Schema
        foreach ($formSchema as $block) {
            $data = $block['data'];
            $fieldName = 'custom_' . $data['name'];
            
            $fieldRules = [];
            if ($data['is_required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($block['type'] === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'extensions:pdf,doc,docx,jpg,jpeg,png';
                $fieldRules[] = 'max:10240';
            } elseif ($block['type'] === 'select') {
                if ($data['is_multiple'] ?? false) {
                    $fieldRules[] = 'array';
                } else {
                    $fieldRules[] = 'string';
                }
            } elseif ($block['type'] === 'checkbox') {
                $fieldRules[] = 'boolean';
            } elseif ($block['type'] === 'rating') {
                $fieldRules[] = 'integer';
                $fieldRules[] = 'min:1';
                $fieldRules[] = 'max:5';
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:2000';
            }

            $rules[$fieldName] = $fieldRules;
        }

        return $rules;
    }

    public function messages()
    {
        $job = $this->route('job');
        $formSchema = $job->form_schema ?? [];

        $messages = [
            'full_name.required'             => 'يرجى إدخال الاسم الكامل.',
            'full_name.min'                  => 'الاسم يجب ألا يقل عن 3 أحرف.',
            'email.required'                 => 'يرجى إدخال البريد الإلكتروني.',
            'email.email'                    => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.unique'                   => 'لقد قمت بالتقديم على هذه الوظيفة مسبقاً بنفس البريد الإلكتروني.',
            'phone.required'                 => 'يرجى إدخال رقم الجوال.',
            'phone.regex'                    => 'صيغة رقم الجوال غير صحيحة (يرجى إدخال رقم هاتف صحيح).',
            'phone.min'                      => 'رقم الجوال يجب ألا يقل عن 8 أرقام.',
            'national_id.required'           => 'يرجى إدخال رقم الهوية الوطنية أو الإقامة.',
            'city.required'                  => 'يرجى إدخال المدينة ومحل الإقامة.',
            'birth_date.required'            => 'يرجى تحديد تاريخ الميلاد.',
            'birth_date.before'              => 'تاريخ الميلاد يجب أن يكون تاريخاً سابقاً.',
            'gender.required'                => 'يرجى تحديد الجنس / النوع.',
            'resume.required'                => 'يرجى إرفاق السيرة الذاتية (CV).',
            'resume.extensions'              => 'يجب أن تكون السيرة الذاتية بصيغة PDF أو DOC أو DOCX.',
            'resume.max'                     => 'حجم السيرة الذاتية يجب ألا يتجاوز 10 ميجابايت.',
            'linkedin_url.required'          => 'يرجى إدخال رابط حساب لينكد إن.',
            'linkedin_url.regex'             => 'يجب أن يكون رابط لينكد إن صحيحاً.',
            'portfolio_url.required'         => 'يرجى إدخال رابط معرض الأعمال أو الموقع.',
            'current_job_title.required'     => 'يرجى إدخال المسمى الوظيفي الحالي.',
            'current_company.required'       => 'يرجى إدخال جهة العمل الحالية.',
            'years_of_experience.required'   => 'يرجى إدخال عدد سنوات الخبرة.',
            'expected_salary.required'       => 'يرجى إدخال الراتب المتوقع.',
            'seniority_level.required'       => 'يرجى تحديد المستوى المهني.',
            'work_preference.required'       => 'يرجى تحديد نظام العمل المفضل.',
            'availability.required'          => 'يرجى تحديد موعد التفرغ والبدء بالعمل.',
            'general_notes.required'         => 'يرجى إدخال الملاحظات المطلوبة.',
        ];

        foreach ($formSchema as $block) {
            $data = $block['data'] ?? [];
            if (empty($data['name'])) continue;

            $fieldName = 'custom_' . $data['name'];
            $label = $data['label'] ?? 'هذا الحقل';

            $messages["{$fieldName}.required"] = "يرجى إدخال/اختيار {$label}.";
            
            if ($block['type'] === 'file') {
                $messages["{$fieldName}.file"] = "يجب أن يكون الملف المرفق ملفاً صحيحاً.";
                $messages["{$fieldName}.extensions"] = "يجب أن يكون الملف بإحدى الصيغ: PDF, DOC, DOCX, JPG, JPEG, PNG.";
                $messages["{$fieldName}.max"] = "حجم الملف يجب ألا يتجاوز 10 ميجابايت.";
            } elseif ($block['type'] === 'rating') {
                $messages["{$fieldName}.min"] = "التقييم يجب أن يكون بين 1 و 5.";
                $messages["{$fieldName}.max"] = "التقييم يجب أن يكون بين 1 و 5.";
            }
        }

        return $messages;
    }
}
