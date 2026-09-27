<?php
$f = 'app/Http/Controllers/ApplicationController.php';
$c = file_get_contents($f);

$oldCreate = <<<EOT
        \$talent = Talent::create([
            'full_name'           => \$validated['full_name'],
            'email'               => \$validated['email'],
            'phone'               => \$validated['phone'],
            'linkedin_url'        => \$validated['linkedin_url'] ?? null,
            'portfolio_url'       => \$validated['portfolio_url'] ?? null,
EOT;

$newCreate = <<<EOT
        \$talent = Talent::create([
            'full_name'           => \$validated['full_name'] ?? 'بدون اسم',
            'email'               => \$validated['email'] ?? null,
            'phone'               => \$validated['phone'] ?? null,
            'linkedin_url'        => \$validated['linkedin_url'] ?? null,
            'portfolio_url'       => \$validated['portfolio_url'] ?? null,
EOT;

$c = str_replace($oldCreate, $newCreate, $c);

// Also apply the dynamic success message
$oldRedirect = <<<EOT
        return redirect()->back()->with('success', 'تم التقديم بنجاح! سيقوم فريق التوظيف بمراجعة ملفك والتواصل معك قريباً.');
EOT;

$newRedirect = <<<EOT
        \$successMessage = \$job->success_message ?: 'تم التقديم بنجاح! سيقوم فريق التوظيف بمراجعة ملفك والتواصل معك قريباً.';
        return redirect()->back()->with('success', strip_tags(\$successMessage));
EOT;

// I'll just regex replace the return redirect line because it might have mojibake
$c = preg_replace(
    "/return redirect\(\)->back\(\)->with\('success', .*?\);/s",
    "        \$successMessage = \$job->success_message ?: 'تم التقديم بنجاح! سيقوم فريق التوظيف بمراجعة ملفك والتواصل معك قريباً.';\n        return redirect()->back()->with('success', strip_tags(\$successMessage));",
    $c
);

file_put_contents($f, $c);
echo "Controller safely updated!";
