<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "project_manager") {
    header("Location: login_page.php");
    exit();
}
require "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Project Manager Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
  <h3 class="text-center text-success">Welcome, <?= $_SESSION["full_name"] ?> 👷</h3>

  <div class="text-end mb-3">
    <a href="add_project_page.php" class="btn btn-primary">+ Add New Project</a>
  </div>

  <h4>Your Projects</h4>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Title</th>
        <th>Budget (KES)</th>
        <th>Progress (%)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $conn->prepare("SELECT * FROM project WHERE project_manager_id = ?");
      $stmt->execute([$_SESSION["user_id"]]);
      $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($projects as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project["title"]) ?></td>
          <td><?= number_format($project["budget"]) ?></td>
          <td><?= $project["progress"] ?>%</td>
          <td>
            <a href="edit_project.php?id=<?= $project['project_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
