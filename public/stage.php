<?php

declare(strict_types=1);

$slug = $_GET['slug'] ?? '';
$action = $_GET['action'] ?? '';

if (!preg_match('/^stage(0[1-9]|10)$/', $slug)) {
    http_response_code(404);
    exit('Stage not found.');
}

$basePath = __DIR__ . '/../stages/' . $slug;

if ($action === 'secret') {
    $secretPath = $basePath . '/secret.php';

    if (!is_file($secretPath)) {
        http_response_code(404);
        exit('Secret endpoint not found.');
    }

    require $secretPath;
    exit;
}

$stagePath = $basePath . '/index.php';

if (!is_file($stagePath)) {
    http_response_code(404);
    exit('Stage not found.');
}

require $stagePath;
