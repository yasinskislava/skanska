<?php
require_once "header.php";

$categories = [
    'Hardware',
    'Software',
    'Network',
    'Access & Permissions',
    'Other',
];

$priorities = ['Low', 'Medium', 'High', 'Critical'];
?>

<main class="py-3">
    <h1 class="h3 mb-3">Report an IT Issue</h1>

    <form action="process_form.php" method="POST" class="row g-3">
        <input type="hidden" name="form_type" value="report_issue">

        <div class="col-md-6">
            <label for="title" class="form-label">Issue Summary</label>
            <input type="text" class="form-control" id="title" name="title" maxlength="100" required>
        </div>

        <div class="col-md-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                <option value="" selected disabled>Choose category...</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= escapeHtml($category) ?>"><?= escapeHtml($category) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label for="priority" class="form-label">Priority</label>
            <select class="form-select" id="priority" name="priority" required>
                <?php foreach ($priorities as $priority): ?>
                    <option value="<?= escapeHtml($priority) ?>" <?= $priority === 'Medium' ? 'selected' : '' ?>>
                        <?= escapeHtml($priority) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Detailed Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit Issue</button>
        </div>
    </form>
</main>

<?php require_once "footer.php"; ?>