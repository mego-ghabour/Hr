<?php

$f = 'app/Filament/Admin/Resources/JobPostingResource.php';
$c = file_get_contents($f);

// We will add a new Tab for 'تخصيص الهوية والرسائل' (Branding and Messages)
$newTab = <<<EOT
                        \Filament\Schemas\Components\Tabs\Tab::make('الهوية والمظهر (Branding)')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                \Filament\Forms\Components\ColorPicker::make('brand_color')
                                    ->label('اللون الأساسي لنموذج التقديم (Primary Color)')
                                    ->helperText('اتركه فارغاً لاستخدام اللون الافتراضي')
                                    ->nullable(),
                                \Filament\Forms\Components\ToggleButtons::make('form_layout')
                                    ->label('شكل نموذج التقديم (Layout)')
                                    ->options([
                                        'single_page' => 'صفحة واحدة طويلة',
                                        'wizard' => 'نظام الخطوات المتعددة (Wizard)'
                                    ])
                                    ->icons([
                                        'single_page' => 'heroicon-m-document',
                                        'wizard' => 'heroicon-m-view-columns',
                                    ])
                                    ->colors([
                                        'single_page' => 'info',
                                        'wizard' => 'success',
                                    ])
                                    ->default('single_page')
                                    ->inline(),
                                \Filament\Forms\Components\RichEditor::make('success_message')
                                    ->label('رسالة النجاح المخصصة (تظهر بعد التقديم)')
                                    ->helperText('مثال: شكراً لك، سيقوم فريقنا بمراجعة أعمالك. (اتركه فارغاً للرسالة الافتراضية)')
                                    ->columnSpanFull(),
                            ]),
EOT;

// Insert the new tab before the Form Builder tab
$search = "\Filament\Schemas\Components\Tabs\Tab::make('تخصيص الحقول الافتراضية')";
$c = str_replace($search, $newTab . "\n                        " . $search, $c);

file_put_contents($f, $c);
echo "Added branding tab!";
