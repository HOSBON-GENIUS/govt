<?php
/**
 * Project Creation Form Page
 * 
 * This page provides a form interface for project managers to create new projects.
 * It includes functionality for basic project details and media upload capabilities.
 * 
 * Connected Pages:
 * - login_page.php: Redirect destination for unauthorized users
 * - navbar.php: Navigation menu inclusion
 * - create_project.php: Backend handler for form submission
 * - index.php: Redirect destination after successful project creation
 * 
 * Required User Role: project_manager
 * Features: Project details input, multiple media upload, AJAX form submission
 */

// Authentication check: Ensure user is logged in and has project manager role
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "project_manager") {
    header("Location: login_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Page metadata and Bootstrap CSS framework integration -->
    <meta charset="UTF-8">
    <title>Add Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Global navigation component -->
<?php include "navbar.php"; ?>

<!-- jQuery library required for AJAX functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container mt-5">
    <h3 class="text-center text-success">Post a New Project</h3>

    <!-- Project submission form with file upload capability -->
    <form id="projectForm" enctype="multipart/form-data">
        <!-- Project core information input fields -->
        <div class="form-group">
            <label>Project Title</label>
            <input type="text" id="title" name="title" class="form-control my-2" required>
        </div>

        <!-- Project detailed information fields -->
        <div class="form-group">
            <label>Description</label>
            <textarea id="description" name="description" class="form-control my-2" required></textarea>
        </div>

        <!-- Financial information -->
        <div class="form-group">
            <label>Budget (KES)</label>
            <input type="number" id="budget" name="budget" class="form-control my-2" required>
        </div>

        <!-- Project timeline inputs -->
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control my-2" required>
        </div>

        <div class="form-group">
            <label>End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control my-2" required>
        </div>

        <!-- Project status tracking -->
        <div class="form-group">
            <label>Progress (%)</label>
            <input type="number" id="progress" name="progress" class="form-control my-2" min="0" max="100" required>
        </div>

        <!-- Media file upload section supporting multiple files -->
        <div class="mb-3">
            <label class="form-label">Project Media</label>
            <input type="file" id="project_media" name="project_media[]" class="form-control" accept="image/*,video/mp4" multiple>
            <small class="text-muted">You can select multiple images (jpg, png, gif) and videos (mp4)</small>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">Add Project</button>
    </form>

    <!-- Dynamic result display area for form submission feedback -->
    <div id="result" class="mt-3 text-center"></div>
</div>

<script>
$(document).ready(function() {
    // Form submission handler with AJAX implementation
    $("#projectForm").on('submit', function(e) {
        e.preventDefault();
        
        // Visual feedback for form processing
        $("#result").html('<div class="alert alert-info">Processing...</div>');
        
        // Create FormData object for file upload handling
        let formData = new FormData(this);

        // AJAX request configuration and execution
        $.ajax({
            url: "create_project.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Response handling and user feedback
                console.log('Response:', response);
                try {
                    let data = JSON.parse(response);
                    if (data.message) {
                        // Success case: show message and redirect
                        $("#result").html(`<div class="alert alert-success">${data.message}</div>`);
                        setTimeout(() => window.location.href = 'index.php', 1500);
                    } else {
                        // Error case: display error message
                        $("#result").html(`<div class="alert alert-danger">${data.error || 'Something went wrong.'}</div>`);
                    }
                } catch(e) {
                    // JSON parse error handling
                    console.error('Parse error:', e);
                    $("#result").html(`<div class="alert alert-danger">Error processing response</div>`);
                    console.log('Raw response:', response);
                }
            },
            error: function(xhr, status, error) {
                // AJAX request error handling
                console.error('Ajax error:', error);
                $("#result").html(`<div class="alert alert-danger">Error: ${error}</div>`);
            }
        });
    });
});
</script>

</body>
</html>
