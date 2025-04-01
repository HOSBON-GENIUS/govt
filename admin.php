<?php
session_start();
require "config.php";

// Ensure only admin access
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center text-primary">Admin Panel</h2>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#users">Manage Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#projects">Manage Projects</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#comments">View Comments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#audits">Audit Reports</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Users Management -->
        <div id="users" class="tab-pane fade show active">
            <h3>Users</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userList">
                    <!-- Users will be loaded here dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Projects Management -->
        <div id="projects" class="tab-pane fade">
            <h3>Projects</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Title</th>
                        <th>Budget</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="projectList">
                    <!-- Projects will be loaded here dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Comments Section -->
        <div id="comments" class="tab-pane fade">
            <h3>Comments</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Comment ID</th>
                        <th>Project ID</th>
                        <th>User ID</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody id="commentList">
                    <!-- Comments will be loaded here dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Audit Reports -->
        <div id="audits" class="tab-pane fade">
            <h3>Audit Reports</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Audit ID</th>
                        <th>Project ID</th>
                        <th>Auditor ID</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody id="auditList">
                    <!-- Audits will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery & Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fetch users
function loadUsers() {
    $.get("fetch_users.php", function(data) {
        $("#userList").html(data);
    });
}

// Fetch projects
function loadProjects() {
    $.get("fetch_projects.php", function(data) {
        $("#projectList").html(data);
    });
}

// Fetch comments
function loadComments() {
    $.get("fetch_comments.php", function(data) {
        $("#commentList").html(data);
    });
}

// Fetch audit reports
function loadAudits() {
    $.get("fetch_audits.php", function(data) {
        $("#auditList").html(data);
    });
}

// Load all data when the page loads
$(document).ready(function() {
    loadUsers();
    loadProjects();
    loadComments();
    loadAudits();
});
</script>

</body>
</html>
