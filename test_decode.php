<?php
$str = "Ø§Ù„ØªÙ‚Ø¯ÙŠÙ…";
echo "utf8_decode: " . utf8_decode($str) . "\n";
echo "mb_convert: " . mb_convert_encoding($str, 'Windows-1252', 'UTF-8') . "\n";
