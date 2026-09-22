<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/helpers/functions.php';

loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/StageManager.php';

Auth::startSession();

$db = Database::connection();

$stageManager = new StageManager($db);

$stages = $stageManager->getStages();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CompanyAdmin VulnLab</title>
</head>

<body>

<h1>CompanyAdmin VulnLab</h1>

<p>Database connection: OK</p>

<h2>Stages</h2>

<ul>
    <?php foreach ($stages as $stage): ?>
        <li>
            Stage <?= htmlspecialchars((string) $stage['stage_number']) ?>
            —
            <?= htmlspecialchars($stage['name']) ?>
            —
            <?= htmlspecialchars($stage['difficulty']) ?>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
