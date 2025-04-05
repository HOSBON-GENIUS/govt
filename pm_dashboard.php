<?php
/**
 * Project Manager Dashboard
 * 
 * This page serves as the control center for project managers to view and manage
 * their assigned projects. It provides project overview and management options.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - navbar.php: Navigation component
 * - login_page.php: Redirect for unauthorized access
 * - add_project_page.php: New project creation
 * - edit_project.php: Project modification
 * 
 * Security:
 * - Restricted to authenticated project managers only
 * - Session-based access control
 */

// Security and initialization
session_start();
// Verify user authentication and role authorization
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "project_manager") {
    header("Location: login_page.php");
    exit();
}
// Database connection setup
require "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document configuration -->
    <meta charset="UTF-8">
    <title>Project Manager Dashboard</title>
    <!-- Bootstrap styling framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Global navigation component -->
<?php include "navbar.php"; ?>

<!-- Dashboard main content -->
<div class="container mt-5">
    <!-- Personalized welcome header -->
    <h3 class="text-center text-success">Welcome, <?= $_SESSION["full_name"] ?> 👷</h3>

    <!-- Project creation shortcut -->
    <div class="text-end mb-3">
        <a href="add_project_page.php" class="btn btn-primary">+ Add New Project</a>
    </div>

    <!-- Projects overview section -->
    <h4>Your Projects</h4>
    <table class="table table-bordered table-striped">
        <!-- Table column definitions -->
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
            // Database query for manager's projects
            $stmt = $conn->prepare("SELECT * FROM project WHERE project_manager_id = ?");
            $stmt->execute([$_SESSION["user_id"]]);
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Project list generation
            foreach ($projects as $project): ?>
                <tr>
                    <!-- Project details with XSS protection -->
                    <td><?= htmlspecialchars($project["title"]) ?></td>
                    <td><?= number_format($project["budget"]) ?></td>
                    <td><?= $project["progress"] ?>%</td>
                    <!-- Project management actions -->
                    <td>
                        <a href="edit_project.php?id=<?= $project['project_id'] ?>" 
                           class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
