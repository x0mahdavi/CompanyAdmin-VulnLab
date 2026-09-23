<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/helpers/functions.php';

loadEnv(__DIR__ . '/../../.env');

require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../../app/core/Auth.php';

Auth::startSession();

$db = Database::connection();

Auth::loginLabUser($db);

header('Content-Type: application/json; charset=utf-8');

$flag = getenv('STAGE01_FLAG');

if ($flag === false || $flag === '') {
    http_response_code(500);

    echo json_encode([
        'error' => 'Stage flag is not configured.',
    ]);

    exit;
}

echo json_encode([
    'flag' => $flag,
]);
