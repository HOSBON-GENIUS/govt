<?php
/**
 * Project Edit Interface
 * 
 * This page provides a form interface for editing existing projects.
 * It allows authorized users (admins and project managers) to modify
 * project details and manage project media files.
 * 
 * Connected Pages:
 * - config.php: Database connection
 * - navbar.php: Navigation component
 * - update_project.php: Handles form submission
 * - login_page.php: Redirect for unauthorized access
 * - index.php: Return destination after successful update
 * 
 * Database Tables:
 * - project: Main project information
 * - media: Project media files
 */

// Initialize session and verify user authorization
session_start();
// Restrict access to admin and project manager roles
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ["admin", "project_manager"])) {
    header("Location: login_page.php");
    exit();
}

// Include database configuration
require "config.php";

// Validate and retrieve project ID from URL
$project_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$project_id) {
    die("Invalid project ID");
}

// Fetch existing project data from database
$stmt = $conn->prepare("SELECT * FROM project WHERE project_id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify project exists
if (!$project) {
    die("Project not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic page configuration and styling -->
    <meta charset="UTF-8">
    <title>Edit Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Include navigation menu -->
<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h3 class="text-center text-primary">Edit Project</h3>

    <!-- Project edit form with file upload capability -->
    <form id="editProjectForm" enctype="multipart/form-data">
        <!-- Hidden project ID field for form submission -->
        <input type="hidden" id="project_id" value="<?= htmlspecialchars($project_id) ?>">
        
        <!-- Project information input fields with existing values -->
        <input type="text" id="title" class="form-control my-2" value="<?= htmlspecialchars($project['title']) ?>" required>
        <textarea id="description" class="form-control my-2" required><?= htmlspecialchars($project['description']) ?></textarea>
        <input type="number" id="budget" class="form-control my-2" value="<?= htmlspecialchars($project['budget']) ?>" required>
        <input type="date" id="start_date" class="form-control my-2" value="<?= htmlspecialchars($project['start_date']) ?>" required>
        <input type="date" id="end_date" class="form-control my-2" value="<?= htmlspecialchars($project['end_date']) ?>" required>
        <input type="number" id="progress" class="form-control my-2" value="<?= htmlspecialchars($project['progress']) ?>" min="0" max="100" required>

        <!-- Display section for existing project media -->
        <div class="mb-3">
            <h5>Current Project Media</h5>
            <div class="row" id="currentMedia">
                <?php
                // Fetch and display existing project media
                $media_stmt = $conn->prepare("SELECT * FROM media WHERE project_id = ? AND comment_id IS NULL");
                $media_stmt->execute([$project_id]);
                while ($media = $media_stmt->fetch()) {
                    echo '<div class="col-md-3 mb-3">';
                    // Display images or videos based on file type
                    if ($media['file_type'] === 'image') {
                        echo '<img src="' . htmlspecialchars($media['file_path']) . '" class="img-thumbnail" style="height: 150px; object-fit: cover;">';
                    } else {
                        echo '<video class="img-thumbnail" style="height: 150px; object-fit: cover;"><source src="' . htmlspecialchars($media['file_path']) . '" type="video/mp4"></video>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- New media upload interface -->
        <div class="mb-3">
            <label class="form-label">Add New Media (Optional)</label>
            <input type="file" id="project_media" name="project_media[]" class="form-control" accept="image/*,video/*" multiple>
            <small class="text-muted">You can select multiple images and videos</small>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Project</button>
    </form>

    <!-- Result message display area -->
    <div id="result" class="mt-3 text-center"></div>
</div>

<!-- JavaScript libraries and form handling -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Form submission handler
$("#editProjectForm").submit(function(e) {
    e.preventDefault();

    // Create FormData object for file upload
    let formData = new FormData();
    // Append form field values
    formData.append('project_id', $("#project_id").val());
    formData.append('title', $("#title").val());
    formData.append('description', $("#description").val());
    formData.append('budget', $("#budget").val());
    formData.append('start_date', $("#start_date").val());
    formData.append('end_date', $("#end_date").val());
    formData.append('progress', $("#progress").val());

    // Handle multiple file uploads
    let mediaFiles = $('#project_media')[0].files;
    for (let i = 0; i < mediaFiles.length; i++) {
        formData.append('project_media[]', mediaFiles[i]);
    }

    // Send AJAX request to update project
    $.ajax({
        url: "update_project.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Handle successful response
            try {
                let data = JSON.parse(response);
                if (data.message) {
                    // Show success message and redirect
                    $("#result").html(`<div class="alert alert-success">${data.message}</div>`);
                    setTimeout(() => window.location.href = 'index.php', 1500);
                } else {
                    // Show error message
                    $("#result").html(`<div class="alert alert-danger">${data.error || 'Something went wrong.'}</div>`);
                }
            } catch(e) {
                // Handle JSON parse errors
                $("#result").html(`<div class="alert alert-danger">Error processing response</div>`);
                console.log(response);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX request errors
            $("#result").html(`<div class="alert alert-danger">Error: ${error}</div>`);
        }
    });
});
</script>

</body>
</html>
