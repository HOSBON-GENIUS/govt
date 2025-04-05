<?php
/**
 * Administrative Control Panel
 * 
 * This page serves as the central management interface for system administrators.
 * It provides comprehensive control over users, projects, comments, and audit reports
 * through an interactive tabbed interface.
 * 
 * Core Functions:
 * - User Management: Account control and role assignments
 * - Project Oversight: Monitor and manage all government projects
 * - Comment Moderation: Review and manage user interactions
 * - Audit Review: Access and monitor project audit reports
 * 
 * Connected Pages:
 * - config.php: Database and system configuration
 * - navbar.php: Site navigation component
 * - login_page.php: Authentication redirect
 * - fetch_users.php: User data API
 * - fetch_projects.php: Project data API
 * - fetch_comments.php: Comments data API
 * - fetch_audits.php: Audit reports API
 * - delete_project.php: Project removal handler
 * 
 * Security Features:
 * - Session-based authentication
 * - Admin-only access control
 */
// Initialize session and include database configuration
session_start();
require "config.php";

// Authentication check: Verify user is logged in and has admin role
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic meta tags and Bootstrap CSS inclusion -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Include navigation bar component -->
<?php include "navbar.php"; ?>

<!-- Main container for admin panel -->
<div class="container mt-5">
    <h2 class="text-center text-primary">Admin Panel</h2>

    <!-- Tab navigation for different admin functions -->
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

    <!-- Tab content containers -->
    <div class="tab-content mt-3">
        <!-- Users Management Tab: Display and manage user accounts -->
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
                    <!-- Dynamic user data placeholder -->
                </tbody>
            </table>
        </div>

        <!-- Projects Management Tab: Display and manage projects -->
        <div id="projects" class="tab-pane fade">
            <h3>Projects</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Title</th>
                        <th>Budget</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projectList">
                    <!-- Dynamic project data placeholder -->
                </tbody>
            </table>
        </div>

        <!-- Comments Section Tab: View all comments -->
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
                    <!-- Dynamic comment data placeholder -->
                </tbody>
            </table>
        </div>

        <!-- Audit Reports Tab: View audit information -->
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
                    <!-- Dynamic audit data placeholder -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include required JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Function to fetch and display user data
function loadUsers() {
    $.get("fetch_users.php", function(data) {
        $("#userList").html(data);
    });
}

// Function to fetch and display project data
function loadProjects() {
    $.get("fetch_projects.php", function(data) {
        $("#projectList").html(data);
    });
}

// Function to fetch and display comment data
function loadComments() {
    $.get("fetch_comments.php", function(data) {
        $("#commentList").html(data);
    });
}

// Function to fetch and display audit report data
function loadAudits() {
    $.get("fetch_audits.php", function(data) {
        $("#auditList").html(data);
    });
}

// Initialize all data when document is ready
$(document).ready(function() {
    loadUsers();
    loadProjects();
    loadComments();
    loadAudits();
});

// Add this new function for project deletion
function deleteProject(projectId) {
    if (confirm('Are you sure you want to delete this project?')) {
        $.ajax({
            url: 'delete_project.php',
            type: 'POST',
            data: { project_id: projectId },
            success: function(response) {
                try {
                    let data = JSON.parse(response);
                    if (data.success) {
                        loadProjects(); // Reload the projects list
                    } else {
                        alert(data.error || 'Failed to delete project');
                    }
                } catch(e) {
                    alert('Error processing response');
                }
            },
            error: function() {
                alert('Error deleting project');
            }
        });
    }
}
</script>

</body>
</html>
