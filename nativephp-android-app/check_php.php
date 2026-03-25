<?php
$supported = ['8.5', '8.4', '8.3'];
$minor = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
echo "Running PHP: " . PHP_VERSION . PHP_EOL;
echo "Minor: " . $minor . PHP_EOL;
echo "Supported: " . json_encode($supported) . PHP_EOL;
echo "Detected: " . (in_array($minor, $supported) ? $minor : '8.3') . PHP_EOL;
