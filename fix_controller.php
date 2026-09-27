<?php
$f = 'app/Http/Controllers/ApplicationController.php';
$c = file_get_contents($f);

// Replace "public function submitJobForm(Request $request, \App\Models\JobPosting $job)"
// with "public function submitJobForm(SubmitJobApplicationRequest $request, \App\Models\JobPosting $job)"
$c = str_replace(
    'public function submitJobForm(Request $request, \App\Models\JobPosting $job)',
    'public function submitJobForm(SubmitJobApplicationRequest $request, \App\Models\JobPosting $job)',
    $c
);

file_put_contents($f, $c);
echo "Fixed controller signature!";
