<?php

declare(strict_types=1);

/**
 * Expected variable:
 * $stage
 */
?>

<div class="col-12 col-md-6 col-xl-4">
    <div class="stage-card p-4 <?= !$stage['unlocked'] ? 'locked' : '' ?>">

        <div class="d-flex justify-content-between align-items-start mb-4">

            <div class="stage-number">
                <?= e((string) $stage['stage_number']) ?>
            </div>

            <?php if ($stage['completed']): ?>

                <span class="badge text-bg-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Completed
                </span>

            <?php elseif ($stage['unlocked']): ?>

                <span class="badge text-bg-primary">
                    <i class="bi bi-unlock me-1"></i>
                    Available
                </span>

            <?php else: ?>

                <span class="badge text-bg-secondary">
                    <i class="bi bi-lock me-1"></i>
                    Locked
                </span>

            <?php endif; ?>

        </div>

        <h5 class="mb-2">
            <?= e($stage['name']) ?>
        </h5>

        <div class="mb-3">
            <span class="badge badge-difficulty">
                <?= e($stage['difficulty']) ?>
            </span>
        </div>

        <p class="text-muted-custom small mb-4">
            <?= e($stage['description']) ?>
        </p>

        <?php if ($stage['unlocked']): ?>

            <a
                href="/stages/<?= e($stage['slug']) ?>/"
                class="btn btn-outline-primary w-100"
            >
                Open Stage
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        <?php else: ?>

            <button
                type="button"
                class="btn btn-outline-secondary w-100"
                disabled
            >
                <i class="bi bi-lock me-1"></i>
                Complete Previous Stage
            </button>

        <?php endif; ?>

    </div>
</div>
