<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/helpers/functions.php';

loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/StageManager.php';

Auth::startSession();

$db = Database::connection();

Auth::loginLabUser($db);

$userId = Auth::id();

if ($userId === null) {
    throw new RuntimeException('Unable to identify lab user.');
}

$stageManager = new StageManager($db);

$stages = $stageManager->getStagesForUser($userId);

$completedCount = $stageManager->completedCount($userId);

$totalStages = count($stages);

$progressPercent = $totalStages > 0
    ? (int) round(($completedCount / $totalStages) * 100)
    : 0;

ob_start();
?>

<div class="hero mb-5">

    <div class="row align-items-center g-4">

        <div class="col-lg-8">

            <div class="text-primary fw-semibold mb-2">
                SECURITY TRAINING ENVIRONMENT
            </div>

            <h1 class="display-5 mb-3">
                CompanyAdmin VulnLab
            </h1>

            <p class="lead text-muted-custom mb-0">
                A hands-on web security laboratory built around
                a fictional company administration platform.
            </p>

        </div>

        <div class="col-lg-4">

            <div class="p-4 rounded-4 border border-secondary-subtle">

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted-custom">
                        Lab Progress
                    </span>

                    <strong>
                        <?= $completedCount ?> / <?= $totalStages ?>
                    </strong>
                </div>

                <div class="progress">

                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: <?= $progressPercent ?>%"
                        aria-valuenow="<?= $progressPercent ?>"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>

                </div>

                <div class="small text-muted-custom mt-2">
                    <?= $progressPercent ?>% completed
                </div>

            </div>

        </div>

    </div>

</div>


<div class="d-flex justify-content-between align-items-end mb-4">

    <div>

        <h2 class="h3 mb-1">
            Training Stages
        </h2>

        <p class="text-muted-custom mb-0">
            Complete each challenge to unlock the next stage.
        </p>

    </div>

    <div class="small text-muted-custom">
        <?= $totalStages ?> challenges
    </div>

</div>


<div class="row g-4">

    <?php foreach ($stages as $stage): ?>

        <?php require __DIR__ . '/../views/components/stage-card.php'; ?>

    <?php endforeach; ?>

</div>

<?php

$content = ob_get_clean();

$title = 'Dashboard · CompanyAdmin VulnLab';

require __DIR__ . '/../views/layouts/main.php';