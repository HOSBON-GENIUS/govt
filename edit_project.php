<?php
session_start();
require "config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "project_manager") {
    header("Location: login_page.php");
    exit();
}

$project_id = $_GET["id"] ?? null;

// Fetch existing project
$stmt = $conn->prepare("SELECT * FROM project WHERE project_id = ? AND project_manager_id = ?");
$stmt->execute([$project_id, $_SESSION["user_id"]]);
$project = $stmt->fetch();

if (!$project) {
    echo "Project not found or access denied.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
  <h3>Edit Project: <?= htmlspecialchars($project["title"]) ?></h3>

  <form action="update_project.php" method="POST">
    <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">
    <input type="text" name="title" class="form-control my-2" value="<?= htmlspecialchars($project['title']) ?>" required>
    <textarea name="description" class="form-control my-2" required><?= htmlspecialchars($project['description']) ?></textarea>
    <input type="number" name="budget" class="form-control my-2" value="<?= $project['budget'] ?>" required>
    <input type="number" name="progress" class="form-control my-2" value="<?= $project['progress'] ?>" min="0" max="100" required>
    <button class="btn btn-success">Update Project</button>
  </form>
</div>

</body>
</html>
