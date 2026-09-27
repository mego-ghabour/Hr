<?php
$content = file_get_contents('app/Filament/Admin/Resources/JobPostingResource.php');
if (strpos($content, 'Tabs::make') !== false) {
    echo "CHANGED";
} else {
    echo "NOT CHANGED";
}
