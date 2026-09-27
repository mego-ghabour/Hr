<?php function test(?string $x) { echo "Inside test!\n"; } try { test(["a"]); } catch (Throwable $e) { echo get_class($e) . ": " . $e->getMessage() . "\n"; }
