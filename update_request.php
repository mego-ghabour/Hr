<?php

$f = 'app/Http/Requests/SubmitJobApplicationRequest.php';
$c = file_get_contents($f);

// We need to wrap full_name, email, phone, linkedin, portfolio in if-statements like the others.
$oldCore = <<<EOT
        // Core Rules
        \$rules = [
            'full_name'           => ['required', 'string', 'min:3', 'max:255'],
            'email'               => [
                'required', 
                'email:rfc,dns', 
                'max:255',
                // Prevent duplicate applications for the same job
                Rule::unique('talents', 'email')->where(function (\$query) use (\$job) {
                    return \$query->where('job_posting_id', \$job->id);
                })
            ],
            'phone'               => ['required', 'string', 'regex:/^\+?[0-9][0-9\s\-\(\)]{7,20}$/', 'min:8', 'max:20'],
            'linkedin_url'        => ['nullable', 'url', 'max:255', 'regex:/^https:\/\/(www\.)?linkedin\.com\/.*$/i'],
            'portfolio_url'       => ['nullable', 'url', 'max:255'],
        ];
EOT;

$newCore = <<<EOT
        \$rules = [];

        if (\$config['show_full_name'] ?? true) {
            \$rules['full_name'] = ['required', 'string', 'min:3', 'max:255'];
        }
        if (\$config['show_email'] ?? true) {
            \$rules['email'] = [
                'required', 
                'email:rfc,dns', 
                'max:255',
                Rule::unique('talents', 'email')->where(function (\$query) use (\$job) {
                    return \$query->where('job_posting_id', \$job->id);
                })
            ];
        }
        if (\$config['show_phone'] ?? true) {
            \$rules['phone'] = ['required', 'string', 'regex:/^\+?[0-9][0-9\s\-\(\)]{7,20}$/', 'min:8', 'max:20'];
        }
        if (\$config['show_linkedin'] ?? true) {
            \$rules['linkedin_url'] = ['nullable', 'url', 'max:255', 'regex:/^https:\/\/(www\.)?linkedin\.com\/.*$/i'];
        }
        if (\$config['show_portfolio'] ?? true) {
            \$rules['portfolio_url'] = ['nullable', 'url', 'max:255'];
        }
EOT;

$c = str_replace($oldCore, $newCore, $c);
file_put_contents($f, $c);
echo "SubmitJobApplicationRequest updated!";
