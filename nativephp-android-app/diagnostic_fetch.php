<?php
require __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

$branch = 'main';
$versionsUrl = "https://bin.nativephp.com/{$branch}/versions.json";
$phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

echo "PHP version: " . $phpVersion . PHP_EOL;

try {
    $manifest = json_decode(
        (new Client)->get($versionsUrl)->getBody()->getContents(),
        true
    );
    echo "Fetched manifest successfully." . PHP_EOL;
    echo "Available versions keys: " . implode(', ', array_keys($manifest['versions'])) . PHP_EOL;
    if (isset($manifest['versions'][$phpVersion])) {
        echo "YES, $phpVersion is in versions." . PHP_EOL;
    } else {
        echo "NO, $phpVersion is NOT in versions." . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Error fetching manifest: " . $e->getMessage() . PHP_EOL;
}
