<?php
require_once "db.php";
require_once "functions.php";

startAppSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$formType = $_POST['form_type'] ?? '';

if ($formType === 'signup') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'employee';

    $allowedRoles = ['employee', 'engineer'];

    if ($username === '' || mb_strlen($username) > 50) {
        setFlashMessage('danger', 'Please provide a valid username (up to 50 characters).');
        header("Location: signup.php?role=" . urlencode($role));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 50) {
        setFlashMessage('danger', 'Please provide a valid email address (up to 50 characters).');
        header("Location: signup.php?role=" . urlencode($role));
        exit;
    }

    if (mb_strlen($password) < 8) {
        setFlashMessage('danger', 'Password must be at least 8 characters long.');
        header("Location: signup.php?role=" . urlencode($role));
        exit;
    }

    if (!in_array($role, $allowedRoles, true)) {
        setFlashMessage('danger', 'Invalid account role specified.');
        header("Location: signup.php");
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $username,
        $email,
        $passwordHash,
        $role,
    ]);

    $newUserId = (int) $pdo->lastInsertId();

    $_SESSION['user_id']  = $newUserId;
    $_SESSION['username'] = $username;
    $_SESSION['role']     = $role;
    setUserPreferenceCookie('last_username', $username);
    setUserPreferenceCookie('last_role', $role);

    setFlashMessage('success', "Account created for {$username} ({$role}).");
    header("Location: report_issue.php");
    exit;
}

if ($formType === 'report_issue') {
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $priority    = trim($_POST['priority'] ?? 'Medium');
    $description = trim($_POST['description'] ?? '');

    $allowedCategories = ['Hardware', 'Software', 'Network', 'Access & Permissions', 'Other'];
    $allowedPriorities = ['Low', 'Medium', 'High', 'Critical'];

    $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
    if ($userId <= 0) {
        $fallbackStmt = $pdo->query("SELECT ID FROM users ORDER BY ID ASC LIMIT 1");
        $fallbackUser = $fallbackStmt->fetch();
        $userId = $fallbackUser ? (int) $fallbackUser['ID'] : 0;
    }

    if ($userId <= 0) {
        setFlashMessage('warning', 'Please sign up first so an employee or engineer profile is linked to the issue.');
        header("Location: signup.php");
        exit;
    }

    if ($title === '' || mb_strlen($title) > 100) {
        setFlashMessage('danger', 'Issue summary is required and must be 100 characters or fewer.');
        header("Location: report_issue.php");
        exit;
    }

    if (!in_array($category, $allowedCategories, true)) {
        setFlashMessage('danger', 'Please select a valid issue category.');
        header("Location: report_issue.php");
        exit;
    }

    if (!in_array($priority, $allowedPriorities, true)) {
        $priority = 'Medium';
    }

    if ($description === '') {
        setFlashMessage('danger', 'Please enter a description of the issue.');
        header("Location: report_issue.php");
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO issues (user_id, title, category, priority, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        $title,
        $category,
        $priority,
        $description,
    ]);

    setUserPreferenceCookie('last_category', $category);
    setFlashMessage('success', 'Issue logged and assigned to the support queue.');
    header("Location: index.php");
    exit;
}

if ($formType === 'login') {
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']) && $_POST['remember_me'] === '1';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        setFlashMessage('danger', 'Please enter both a valid email address and password.');
        header("Location: login.php");
        exit;
    }

    $stmt = $pdo->prepare(
        "SELECT ID, username, email, password, role FROM users WHERE email = :email LIMIT 1"
    );
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        setFlashMessage('danger', 'Invalid email or password.');
        header("Location: login.php");
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['user_id']  = (int) $user['ID'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    if ($rememberMe) {
        setUserPreferenceCookie('last_username', $user['username'], 86400 * 14);
        setUserPreferenceCookie('last_role', $user['role'], 86400 * 14);
    }

    setFlashMessage('success', "Welcome back, {$user['username']} ({$user['role']}).");
    header("Location: index.php");
    exit;
}

setFlashMessage('danger', 'Unknown form submission.');
header("Location: index.php");
exit;