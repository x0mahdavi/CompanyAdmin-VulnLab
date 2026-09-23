<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/helpers/functions.php';

loadEnv(__DIR__ . '/../../.env');

require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../../app/core/Auth.php';
require_once __DIR__ . '/../../app/core/StageManager.php';

Auth::startSession();

$db = Database::connection();

Auth::loginLabUser($db);

$userId = Auth::id();

$stageManager = new StageManager($db);
$flagMessage = null;
$flagSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedFlag = trim($_POST['flag'] ?? '');

    if ($submittedFlag === '') {
        $flagMessage = 'Please enter a flag.';
    } else {
        $stmt = $db->prepare(
            'SELECT flag_hash
             FROM flags
             WHERE stage_id = (
                 SELECT id
                 FROM stages
                 WHERE stage_number = 1
                 LIMIT 1
             )
             LIMIT 1'
        );

        $stmt->execute();

        $flagHash = $stmt->fetchColumn();

        if (
            $flagHash !== false
            && hash_equals(
                $flagHash,
                hash('sha256', $submittedFlag)
            )
        ) {
            $stageId = $stageManager->getStage(1)['id'];

            $stmt = $db->prepare(
                'INSERT INTO progress
                    (user_id, stage_id, completed, completed_at)
                 VALUES
                    (:user_id, :stage_id, 1, CURRENT_TIMESTAMP)
                 ON DUPLICATE KEY UPDATE
                    completed = 1,
                    completed_at = CURRENT_TIMESTAMP'
            );

            $stmt->execute([
                'user_id' => $userId,
                'stage_id' => $stageId,
            ]);

            $flagSuccess = true;
            $flagMessage = 'Stage 01 completed. Stage 02 is now unlocked.';
        } else {
            $flagMessage = 'Invalid flag.';
        }
    }
}
if (!$stageManager->isUnlocked($userId, 1)) {
    http_response_code(403);
    exit('Stage is locked.');
}

$search = $_GET['q'] ?? '';

$employees = [];

if ($search !== '') {
    $stmt = $db->prepare(
        'SELECT
            employee_code,
            full_name,
            department,
            email,
            job_title
         FROM stage01_employees
         WHERE full_name LIKE :name_search
            OR employee_code LIKE :code_search
            OR department LIKE :department_search
         ORDER BY full_name'
    );

    $searchTerm = '%' . $search . '%';

    $stmt->execute([
        'name_search' => $searchTerm,
        'code_search' => $searchTerm,
        'department_search' => $searchTerm,
    ]);

    $employees = $stmt->fetchAll();
}

ob_start();
?>

<div class="row justify-content-center">

    <div class="col-lg-10">

        <div class="mb-4">

            <a
                href="/"
                class="text-decoration-none text-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Dashboard
            </a>

        </div>

        <div class="hero mb-4">

            <div class="d-flex justify-content-between align-items-start gap-3">

                <div>

                    <div class="text-primary fw-semibold mb-2">
                        STAGE 01
                    </div>

                    <h1 class="h2 mb-2">
                        Employee Search
                    </h1>

                    <p class="text-muted-custom mb-0">
                        Search the company's employee directory.
                    </p>

                </div>

                <span class="badge text-bg-primary">
                    Easy
                </span>

            </div>

        </div>

        <div class="stage-card p-4 mb-4">

            <form method="GET">

                <label
                    for="search"
                    class="form-label"
                >
                    Search employees
                </label>

                <div class="input-group">

                    <input
                        type="text"
                        id="search"
                        name="q"
                        class="form-control bg-dark text-light border-secondary"
                        value="<?= e($search) ?>"
                        placeholder="Name, employee code, department..."
                    >

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Search
                    </button>

                </div>

            </form>

        </div>

        <?php if ($search !== ''): ?>

            <div class="mb-3 text-muted-custom">
                Search results for:
                <strong><?= $search ?></strong>
            </div>

        <?php endif; ?>
        <div class="stage-card p-4 mb-4">

    <div class="d-flex align-items-center gap-2 mb-2">
        <i class="bi bi-flag text-primary"></i>

        <h5 class="mb-0">
            Submit Flag
        </h5>
    </div>

    <p class="text-muted-custom small mb-3">
        Enter the flag you discovered while solving this stage.
    </p>

    <?php if ($flagMessage !== null): ?>

        <div class="alert <?= $flagSuccess ? 'alert-success' : 'alert-danger' ?>">
            <?= e($flagMessage) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="input-group">

            <input
                type="text"
                name="flag"
                class="form-control bg-dark text-light border-secondary"
                placeholder="FLAG-STAGE01-..."
                autocomplete="off"
            >

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check2-circle me-1"></i>
                Submit Flag
            </button>

        </div>

    </form>

</div>

        <div class="row g-3">

            <?php foreach ($employees as $employee): ?>

                <div class="col-md-6">

                    <div class="stage-card p-4">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="stage-number">
                                <i class="bi bi-person"></i>
                            </span>

                            <span class="badge badge-difficulty">
                                <?= e($employee['employee_code']) ?>
                            </span>

                        </div>

                        <h5>
                            <?= e($employee['full_name']) ?>
                        </h5>

                        <p class="text-muted-custom mb-2">
                            <?= e($employee['job_title']) ?>
                        </p>

                        <div class="small text-secondary">

                            <div>
                                <i class="bi bi-building me-1"></i>
                                <?= e($employee['department']) ?>
                            </div>

                            <div class="mt-1">
                                <i class="bi bi-envelope me-1"></i>
                                <?= e($employee['email']) ?>
                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <?php if ($search !== '' && count($employees) === 0): ?>

            <div class="alert alert-secondary mt-4">
                No employees matched your search.
            </div>

        <?php endif; ?>

    </div>

</div>

<?php

$content = ob_get_clean();

$title = 'Stage 01 · Employee Search';

require __DIR__ . '/../../views/layouts/main.php';
