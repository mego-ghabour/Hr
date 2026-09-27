<?php
$str = "Ø§Ù„ØªÙ‚Ø¯ÙŠÙ… Ù„ÙˆØ¸ÙŠÙ Ø©: hello عربى";
$fixed = preg_replace_callback('/(?:[ØÙ][\x80-\xBF])+/', function($m) {
    return utf8_decode($m[0]);
}, $str);
echo "Result hex: " . bin2hex($fixed) . "\n";
echo "Expected hex: " . bin2hex("التقديم لوظيفة: hello عربى") . "\n";
