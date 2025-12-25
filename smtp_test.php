<?php
$fp = fsockopen("smtp.gmail.com", 587, $errno, $errstr, 10);
if (!$fp) {
    echo "❌ BLOCKED: $errstr ($errno)";
} else {
    echo "✅ CONNECTED";
    fclose($fp);
}
