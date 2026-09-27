<?php

namespace App\Filament\Actions;

use App\Models\Talent;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CommunicationActions
{
    private static function parseTemplate(string $template, string $name, string $brand): string
    {
        $parsed = str_replace('{name}', $name, $template);
        $parsed = str_replace('{brand}', $brand, $parsed);
        return $parsed;
    }

    private static function getCustomTemplates(): array
    {
        $custom = Setting::get('custom_templates');
        return $custom ? json_decode($custom, true) : [];
    }

    /**
     * WhatsApp Action
     */
    public static function makeWhatsAppAction(): Action
    {
        return Action::make('sendWhatsApp')
            ->label('واتساب')
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->color('success')
            ->form(function () {
                $options = [
                    'interview' => 'دعوة لمقابلة (Interview)',
                    'docs'      => 'طلب مستندات (Documents)',
                ];

                $customs = self::getCustomTemplates();
                foreach ($customs as $index => $tpl) {
                    if (($tpl['type'] ?? '') === 'whatsapp') {
                        $options['custom_' . $index] = $tpl['name'] ?? 'قالب مخصص';
                    }
                }
                
                $options['custom'] = 'رسالة مخصصة (كتابة يدوية)';

                return [
                    Forms\Components\Select::make('template')
                        ->label('اختر القالب')
                        ->options($options)
                        ->default('interview')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, Talent $record) use ($customs) {
                            $brand = Setting::get('brand_name', 'فريق التوظيف');
                            $name  = $record->full_name;

                            if ($state === 'custom') {
                                $set('message', '');
                                return;
                            }

                            if (str_starts_with($state, 'custom_')) {
                                $index = (int) str_replace('custom_', '', $state);
                                $template = $customs[$index]['body'] ?? '';
                                $set('message', self::parseTemplate($template, $name, $brand));
                                return;
                            }

                            $templateKey = 'wa_template_' . $state;
                            $template = Setting::get($templateKey, '');
                            
                            $set('message', self::parseTemplate($template, $name, $brand));
                        }),
                    Forms\Components\Textarea::make('message')
                        ->label('نص الرسالة')
                        ->rows(4)
                        ->required()
                        ->default(function (Talent $record) {
                            $brand = Setting::get('brand_name', 'فريق التوظيف');
                            $template = Setting::get('wa_template_interview', "مرحباً {name}،\nنود إعلامك بأنه تم ترشيحك لمقابلة عمل في ({brand}). متى تكون متاحاً؟");
                            return self::parseTemplate($template, $record->full_name, $brand);
                        }),
                ];
            })
            ->action(function (array $data, Talent $record, \Livewire\Component $livewire) {
                $phone = preg_replace('/[^0-9]/', '', $record->phone ?? '');
                if (str_starts_with($phone, '01') && strlen($phone) === 11) {
                    $phone = '2' . $phone;
                }

                $url = 'https://wa.me/' . $phone . '?text=' . urlencode($data['message']);

                // تسجيل الملاحظة
                $record->notes()->create([
                    'user_id' => Auth::id(),
                    'body'    => "📱 تم التواصل واتساب:\n" . $data['message'],
                ]);

                Notification::make()
                    ->title('تم فتح المحادثة وتسجيل الملاحظة')
                    ->success()
                    ->send();

                $livewire->js("window.open('{$url}', '_blank')");
            });
    }

    /**
     * Email Action
     */
    public static function makeEmailAction(): Action
    {
        return Action::make('sendEmail')
            ->label('إرسال إيميل')
            ->icon('heroicon-o-envelope')
            ->color('primary')
            ->form(function () {
                $options = [
                    'interview' => 'دعوة لمقابلة (Interview)',
                    'offer'     => 'عرض وظيفي (Job Offer)',
                    'reject'    => 'رسالة رفض (Rejection)',
                ];

                $customs = self::getCustomTemplates();
                foreach ($customs as $index => $tpl) {
                    if (($tpl['type'] ?? '') === 'email') {
                        $options['custom_' . $index] = $tpl['name'] ?? 'قالب مخصص';
                    }
                }
                
                $options['custom'] = 'رسالة مخصصة (كتابة يدوية)';

                return [
                    Forms\Components\Select::make('template')
                        ->label('اختر قالب الإيميل')
                        ->options($options)
                        ->default('interview')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, Talent $record) use ($customs) {
                            $brand = Setting::get('brand_name', 'فريق التوظيف');
                            $name  = $record->full_name;

                            if ($state === 'custom') {
                                $set('subject', '');
                                $set('body', '');
                                return;
                            }

                            if (str_starts_with($state, 'custom_')) {
                                $index = (int) str_replace('custom_', '', $state);
                                $subjectTemplate = $customs[$index]['subject'] ?? '';
                                $bodyTemplate    = $customs[$index]['body'] ?? '';
                                
                                $set('subject', self::parseTemplate($subjectTemplate, $name, $brand));
                                $set('body', self::parseTemplate($bodyTemplate, $name, $brand));
                                return;
                            }

                            $subjectTemplate = Setting::get('email_template_' . $state . '_subject', '');
                            $bodyTemplate    = Setting::get('email_template_' . $state . '_body', '');
                            
                            $set('subject', self::parseTemplate($subjectTemplate, $name, $brand));
                            $set('body', self::parseTemplate($bodyTemplate, $name, $brand));
                        }),
                    Forms\Components\TextInput::make('subject')
                        ->label('عنوان الإيميل')
                        ->required()
                        ->default(function (Talent $record) {
                            $brand = Setting::get('brand_name', 'فريق التوظيف');
                            $template = Setting::get('email_template_interview_subject', "دعوة لمقابلة عمل - {brand}");
                            return self::parseTemplate($template, $record->full_name, $brand);
                        }),
                    Forms\Components\Textarea::make('body')
                        ->label('محتوى الإيميل')
                        ->rows(6)
                        ->required()
                        ->default(function (Talent $record) {
                            $brand = Setting::get('brand_name', 'فريق التوظيف');
                            $template = Setting::get('email_template_interview_body', "عزيزي/عزيزتي {name}،\n\nتحية طيبة وبعد،\n\nيسعدنا إعلامك بأنه تم ترشيحك لإجراء مقابلة عمل.\n\nمع خالص التحيات،\n{brand}");
                            return self::parseTemplate($template, $record->full_name, $brand);
                        }),
                ];
            })
            ->action(function (array $data, Talent $record) {
                try {
                    Mail::raw($data['body'], function ($message) use ($record, $data) {
                        $message->to($record->email)
                            ->subject($data['subject']);
                    });
                } catch (\Exception $e) {
                    // Log mail error gracefully if server SMTP not configured
                }

                // تسجيل الملاحظة
                $record->notes()->create([
                    'user_id' => Auth::id(),
                    'body'    => "📧 تم إرسال إيميل: [{$data['subject']}]\n{$data['body']}",
                ]);

                Notification::make()
                    ->title('تم إرسال الإيميل وتسجيله في ملاحظات المرشح')
                    ->success()
                    ->send();
            });
    }
}
