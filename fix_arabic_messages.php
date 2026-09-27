<?php
$f = 'app/Http/Requests/SubmitJobApplicationRequest.php';
$c = file_get_contents($f);

// Find the messages method and replace its body
$pattern = '/public function messages\(\)\s*\{.*?\n    \}/s';
$newMessages = <<<EOT
public function messages()
    {
        \$job = \$this->route('job');
        \$formSchema = \$job->form_schema ?? [];

        \$messages = [
            'full_name.required'     => 'يرجى إدخال الاسم الكامل.',
            'full_name.min'          => 'الاسم يجب ألا يقل عن 3 أحرف.',
            'email.required'         => 'يرجى إدخال البريد الإلكتروني.',
            'email.email'            => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.unique'           => 'لقد قمت بالتقديم على هذه الوظيفة مسبقاً بنفس البريد الإلكتروني.',
            'phone.required'         => 'يرجى إدخال رقم الجوال.',
            'phone.regex'            => 'صيغة رقم الجوال غير صحيحة (يرجى إدخال رقم صحيح).',
            'phone.min'              => 'رقم الجوال يجب ألا يقل عن 8 أرقام.',
            'resume.required'        => 'يرجى إرفاق السيرة الذاتية (CV).',
            'resume.mimes'           => 'يجب أن تكون السيرة الذاتية بصيغة PDF أو DOC أو DOCX.',
            'resume.max'             => 'حجم السيرة الذاتية يجب ألا يتجاوز 10 ميجابايت.',
            'linkedin_url.regex'     => 'يجب أن يكون رابط لينكد إن صحيحاً.',
        ];

        foreach (\$formSchema as \$block) {
            \$data = \$block['data'] ?? [];
            if (empty(\$data['name'])) continue;

            \$fieldName = 'custom_' . \$data['name'];
            \$label = \$data['label'] ?? 'هذا الحقل';

            \$messages["{\$fieldName}.required"] = "يرجى إدخال/اختيار {\$label}.";
            
            if (\$block['type'] === 'file') {
                \$messages["{\$fieldName}.file"] = "يجب أن يكون الملف المرفق ملفاً صحيحاً.";
                \$messages["{\$fieldName}.mimes"] = "يجب أن يكون الملف بإحدى الصيغ: PDF, DOC, DOCX, JPG, JPEG, PNG.";
                \$messages["{\$fieldName}.max"] = "حجم الملف يجب ألا يتجاوز 10 ميجابايت.";
            } elseif (\$block['type'] === 'rating') {
                \$messages["{\$fieldName}.min"] = "التقييم يجب أن يكون بين 1 و 5.";
                \$messages["{\$fieldName}.max"] = "التقييم يجب أن يكون بين 1 و 5.";
            }
        }

        return \$messages;
    }
EOT;

$c = preg_replace($pattern, $newMessages, $c);
file_put_contents($f, $c);
echo "Arabic messages updated successfully!";
