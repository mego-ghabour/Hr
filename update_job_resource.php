<?php

$f = 'app/Filament/Admin/Resources/JobPostingResource.php';
$c = file_get_contents($f);

$newToggles = <<<EOT
                                        Forms\Components\Toggle::make('primary_fields_config.show_full_name')
                                            ->label('حقل "الاسم الكامل" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_email')
                                            ->label('حقل "البريد الإلكتروني" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_phone')
                                            ->label('حقل "رقم الجوال" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_linkedin')
                                            ->label('حقل "لينكد إن"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_portfolio')
                                            ->label('حقل "معرض الأعمال"')->default(true),
EOT;

// Insert new toggles before the first toggle.
$search = "Forms\Components\Toggle::make('primary_fields_config.show_current_job_title')";
$c = str_replace($search, $newToggles . "\n                                        " . $search, $c);

file_put_contents($f, $c);
echo "JobPostingResource updated!";
