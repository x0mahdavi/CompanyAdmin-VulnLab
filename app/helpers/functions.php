<?php

declare(strict_types=1);

function loadEnv(string $path): void
{
    if (!is_readable($path)) {
        throw new RuntimeException('.env file not found.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(
            explode('=', $line, 2),
            2,
            ''
        );

        $key = trim($key);
        $value = trim($value);

        if ($key !== '') {
            putenv($key . '=' . $value);
        }
    }
}
