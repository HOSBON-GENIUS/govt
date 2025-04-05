<?php
/**
 * Project Details Page
 * 
 * This page displays detailed information about a specific project, including:
 * - Project overview and statistics
 * - Media gallery (images and videos)
 * - Comments section with media upload capability
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - navbar.php: Navigation component
 * - view_comments.php: Comments retrieval
 * - add_comment.php: Comment submission handler
 * 
 * Database Tables:
 * - project: Main project information
 * - project_media: Project-related media files
 * - comments: User comments and discussions
 */

// Initialize core components
session_start();
require "config.php";

// Validate project identifier
$project_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$project_id) {
    echo "<div class='container mt-5'><h4 class='text-danger'>Invalid Project ID.</h4></div>";
    exit;
}

// Retrieve project information
$stmt = $conn->prepare("SELECT * FROM project WHERE project_id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle non-existent projects
if (!$project) {
    echo "<div class='container mt-5'><h4 class='text-danger'>Project not found.</h4></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document configuration and resources -->
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($project['title']) ?> - Project Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include "navbar.php"; ?>

<!-- Project information display -->
<div class="container mt-4">
    <!-- Project header information -->
    <h2 class="text-primary"><?= htmlspecialchars($project["title"]) ?></h2>
    <p><?= nl2br(htmlspecialchars($project["description"])) ?></p>

    <!-- Project metrics and timeline -->
    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Start Date:</strong> <?= $project["start_date"] ?></li>
        <li class="list-group-item"><strong>End Date:</strong> <?= $project["end_date"] ?></li>
        <li class="list-group-item"><strong>Budget:</strong> Ksh <?= number_format($project["budget"]) ?></li>
        <li class="list-group-item"><strong>Progress:</strong> <?= $project["progress"] ?>%</li>
    </ul>

    <!-- Media gallery implementation -->
    <div class="project-media mb-4">
        <h4 class="mb-3">Project Media</h4>
        <div class="row g-3">
        <?php
        // Fetch project media files
        $media_stmt = $conn->prepare("SELECT * FROM project_media WHERE project_id = ? ORDER BY uploaded_at DESC");
        $media_stmt->execute([$project_id]);
        
        // Media rendering helper function
        function renderMedia($media) {
            // Generate media display card based on file type
            $output = "<div class='col-md-4 col-lg-3'><div class='card h-100'>";
            
            // Handle image files
            if ($media['file_type'] === 'image') {
                $output .= "<a href='" . htmlspecialchars($media['file_path']) . "' target='_blank'>";
                $output .= "<img src='" . htmlspecialchars($media['file_path']) . 
                          "' class='card-img-top' style='height: 200px; object-fit: cover;' alt='Project Image'>";
                $output .= "</a>";
            } 
            // Handle video files
            else {
                $output .= "<div class='ratio ratio-16x9'>";
                $output .= "<video controls><source src='" . htmlspecialchars($media['file_path']) . 
                          "' type='video/mp4'>Your browser does not support video.</video>";
                $output .= "</div>";
            }
            
            // Add upload timestamp
            $output .= "<div class='card-footer bg-light'>";
            $output .= "<small class='text-muted'>Uploaded: " . date('M d, Y', strtotime($media['uploaded_at'])) . "</small>";
            $output .= "</div></div></div>";
            return $output;
        }

        // Display media gallery or empty state message
        $mediaCount = 0;
        while ($media = $media_stmt->fetch()) {
            echo renderMedia($media);
            $mediaCount++;
        }
        
        if ($mediaCount === 0) {
            echo "<div class='col-12'><p class='text-muted'>No media files available for this project.</p></div>";
        }
        ?>
        </div>
    </div>

    <!-- Comments section implementation -->
    <div class="card mt-5">
        <div class="card-header bg-light">
            <h5 class="mb-0">💬 Comments</h5>
        </div>
        <div class="card-body">
            <!-- Comment submission interface -->
            <form id="commentForm" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>">
                
                <div class="mb-2">
                    <textarea name="comment_text" class="form-control" placeholder="Leave a comment..." required></textarea>
                </div>
        
                <div class="mb-2">
                    <label class="form-label">Optional: Upload Image or Video</label>
                    <input type="file" name="media" class="form-control" accept="image/*,video/*">
                </div>
        
                <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                <div id="commentStatus" class="mt-2 small text-success"></div>
            </form>

            <hr>

            <!-- Dynamic comments display -->
            <div id="commentsSection">
                <div class="text-muted">Loading comments...</div>
            </div>
        </div>
    </div>
</div>

<script>
// Comments functionality implementation
$(document).ready(function(){
    // Initialize comments display
    loadComments();

    // Comments fetching function
    function loadComments() {
        $.get("view_comments.php", { project_id: <?= $project_id ?> }, function(data){
            $("#commentsSection").html(data);
        });
    }

    // Status update helper
    function updateCommentStatus(message, isError = false) {
        $("#commentStatus")
            .text(message)
            .removeClass(isError ? 'text-success' : 'text-danger')
            .addClass(isError ? 'text-danger' : 'text-success');
    }

    // Comment submission handler
    $("#commentForm").submit(function(e){
        e.preventDefault();
        let formData = new FormData(this);

        // Process comment submission
        $.ajax({
            url: "add_comment.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                try {
                    let data = JSON.parse(response);
                    if (data.message) {
                        updateCommentStatus(data.message);
                        $("#commentForm")[0].reset();
                        loadComments();
                    } else {
                        updateCommentStatus(data.error || "Something went wrong.", true);
                    }
                } catch(e) {
                    updateCommentStatus("Error processing response", true);
                    console.log(response);
                }
            },
            error: function(xhr, status, error) {
                updateCommentStatus("Error: " + error, true);
            }
        });
    });
});
</script>

</body>
</html>
