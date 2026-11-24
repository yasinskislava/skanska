<?php
require_once "functions.php";
startAppSession();
$flash = consumeFlashMessage();
$activeUser = $_SESSION['username'] ?? null;
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Skanska Support Portal</title>
</head>
<body>
    <header class="mb-4">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Skanska</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav me-auto">
                        <a class="nav-link" href="index.php">Issues &amp; Search</a>
                        <a class="nav-link" href="signup.php?role=employee">Employee Sign up</a>
                        <a class="nav-link" href="signup.php?role=engineer">Engineer Sign up</a>
                        <a class="nav-link" href="report_issue.php">Report Issue</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a class="nav-link" href="logout.php">Sign out</a>
                        <?php else: ?>
                            <a class="nav-link" href="login.php">Log in</a>
                        <?php endif; ?>
                    </div>
                    <?php if ($activeUser !== null): ?>
                        <span class="navbar-text small">
                            Signed in: <strong><?= escapeHtml($activeUser) ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
        <?php if ($flash !== null): ?>
            <div class="alert alert-<?= escapeHtml($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= escapeHtml($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>