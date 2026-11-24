<?php
require_once "header.php";

$requestedRole = $_GET['role'] ?? 'employee';
$role = in_array($requestedRole, ['employee', 'engineer'], true) ? $requestedRole : 'employee';
?>

<main class="py-3">
    <h1 class="h3 mb-3">
        <?= $role === 'engineer' ? 'IT Engineer Sign Up' : 'Employee Sign Up' ?>
    </h1>

    <form action="process_form.php" method="POST" class="row g-3 needs-validation" novalidate>
        <input type="hidden" name="form_type" value="signup">
        <input type="hidden" name="role" value="<?= escapeHtml($role) ?>">

        <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" maxlength="50" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Work Email</label>
            <input type="email" class="form-control" id="email" name="email" maxlength="50" required>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" minlength="8" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Create Account</button>
        </div>
    </form>
</main>

<?php require_once "footer.php"; ?>