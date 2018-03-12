<?php
declare(strict_types=1);

// THIS SCRIPT IS USED TO DYNAMICALLY ALTER THE PHP DOCKERFILE TO USE THE VERSION OF
// PHP THAT TRAVIS IS RUNNING, SO ALL CHECKS RUN AGAINST ALL PHP VERSIONS

list($major, $minor, $patch) = explode('.', PHP_VERSION);

$version = "{$major}.{$minor}";

$image = [
    '7.0' => 'php:7.0-fpm',
    '7.1' => 'php:7.1-fpm',
    '7.2' => 'php:7.2-fpm',
][$version] ?? 'php:7.0-fpm';

$dockerFile = __DIR__ . '/docker/php/Dockerfile';

$dockerFileContents = array_map('trim', file($dockerFile));

$dockerFileContents[0] = "FROM {$image}";

file_put_contents($dockerFile, implode(PHP_EOL, $dockerFileContents) . PHP_EOL);

echo "Using {$image} as PHP image", PHP_EOL;
