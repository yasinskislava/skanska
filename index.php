<?php
require_once "db.php";
require_once "header.php";

$search   = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

$allowedCategories = ['Hardware', 'Software', 'Network', 'Access & Permissions', 'Other'];

$sql = "SELECT
            i.issue_id,
            i.title,
            i.category,
            i.priority,
            i.description,
            i.created_at,
            u.username,
            u.email,
            u.role
        FROM issues i
        INNER JOIN users u ON i.user_id = u.ID
        WHERE 1=1";

$params = [];

if ($search !== '') {
    $sql .= " AND (i.title LIKE :search OR i.description LIKE :search OR u.username LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if ($category !== '' && in_array($category, $allowedCategories, true)) {
    $sql .= " AND i.category = :category";
    $params[':category'] = $category;
}

$sql .= " ORDER BY i.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$issues = $stmt->fetchAll();
?>

<main class="py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Logged Issues</h1>
        <a href="report_issue.php" class="btn btn-sm btn-primary">+ New Issue</a>
    </div>

    <form action="index.php" method="GET" class="row g-2 mb-4">
        <div class="col-md-6">
            <input
                type="search"
                class="form-control"
                name="q"
                placeholder="Search title, description, or reporter username..."
                value="<?= escapeHtml($search) ?>"
            >
        </div>
        <div class="col-md-4">
            <select class="form-select" name="category">
                <option value="">All Categories</option>
                <?php foreach ($allowedCategories as $cat): ?>
                    <option value="<?= escapeHtml($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                        <?= escapeHtml($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Issue</th>
                    <th scope="col">Category</th>
                    <th scope="col">Priority</th>
                    <th scope="col">Reported By</th>
                    <th scope="col">Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($issues)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No matching issues found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($issues as $issue): ?>
                        <tr>
                            <th scope="row"><?= escapeHtml((string) $issue['issue_id']) ?></th>
                            <td>
                                <div class="fw-semibold"><?= escapeHtml($issue['title']) ?></div>
                                <div class="small text-muted"><?= escapeHtml($issue['description']) ?></div>
                            </td>
                            <td><?= escapeHtml($issue['category']) ?></td>
                            <td><?= escapeHtml($issue['priority']) ?></td>
                            <td>
                                <?= escapeHtml($issue['username']) ?>
                                <div class="small text-muted"><?= escapeHtml($issue['email']) ?></div>
                            </td>
                            <td><?= escapeHtml($issue['role']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once "footer.php"; ?>