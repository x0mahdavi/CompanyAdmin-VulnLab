<?php

declare(strict_types=1);

/**
 * Expected variables:
 * $title
 * $content
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= e($title ?? 'CompanyAdmin VulnLab') ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="/assets/css/app.css"
    >

</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">

    <div class="container py-2">

        <a
            class="navbar-brand text-light fw-semibold d-flex align-items-center gap-2"
            href="/"
        >
            <span class="brand-mark">
                <i class="bi bi-shield-lock"></i>
            </span>

            CompanyAdmin
        </a>

        <span class="small text-secondary">
            VulnLab
        </span>

    </div>

</nav>

<main class="container py-5">

    <?= $content ?>

</main>

<footer class="mt-5 py-4">

    <div class="container text-center small">
        CompanyAdmin VulnLab · Local Security Training Environment
    </div>

</footer>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
