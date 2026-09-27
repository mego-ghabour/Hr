<?php
$f = 'app/Models/JobPosting.php';
$c = file_get_contents($f);
$c = str_replace(
    "'is_active',",
    "'is_active',\n        'success_message',\n        'brand_color',\n        'form_layout',",
    $c
);
file_put_contents($f, $c);
echo "JobPosting model updated!";
