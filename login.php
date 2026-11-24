<?php
require_once "header.php";
?>

<main class="py-3">
    <h1 class="h3 mb-3">Sign In</h1>

    <form action="process_form.php" method="POST" class="row g-3" novalidate>
        <input type="hidden" name="form_type" value="login">

        <div class="col-md-6">
            <label for="email" class="form-label">Work Email</label>
            <input type="email" class="form-control" id="email" name="email" maxlength="50" required>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" value="1">
                <label class="form-check-label" for="remember_me">
                    Remember my username in a cookie
                </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
    </form>
</main>

<?php require_once "footer.php"; ?>