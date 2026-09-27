<?php
$f = 'app/Filament/Admin/Resources/JobPostingResource.php';
$c = file_get_contents($f);
$c = str_replace('\Filament\Forms\Components\Tabs', '\Filament\Schemas\Components\Tabs', $c);
$c = str_replace('\Filament\Forms\Components\Fieldset', '\Filament\Schemas\Components\Fieldset', $c);
file_put_contents($f, $c);
echo 'Fixed!';
