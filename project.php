<?php
session_start();
require "config.php";

// Get and validate project ID
$project_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : null;

if (!$project_id) {
    echo "<div class='container mt-5'><h4 class='text-danger'>Invalid Project ID.</h4></div>";
    exit;
}

// Fetch project details
$stmt = $conn->prepare("SELECT * FROM project WHERE project_id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    echo "<div class='container mt-5'><h4 class='text-danger'>Project not found.</h4></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($project['title']) ?> - Project Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-4">
  <h2 class="text-primary"><?= htmlspecialchars($project["title"]) ?></h2>
  <p><?= nl2br(htmlspecialchars($project["description"])) ?></p>

  <ul class="list-group mb-4">
    <li class="list-group-item"><strong>Start Date:</strong> <?= $project["start_date"] ?></li>
    <li class="list-group-item"><strong>End Date:</strong> <?= $project["end_date"] ?></li>
    <li class="list-group-item"><strong>Budget:</strong> Ksh <?= number_format($project["budget"]) ?></li>
    <li class="list-group-item"><strong>Progress:</strong> <?= $project["progress"] ?>%</li>
  </ul>

  <!-- ✅ Comments Section -->
  <div class="card mt-5">
    <div class="card-header bg-light">
      <h5 class="mb-0">💬 Comments</h5>
    </div>
    <div class="card-body">
      <!-- Comment form (anyone can post) -->
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

      <!-- Comments will appear here -->
      <div id="commentsSection">
        <div class="text-muted">Loading comments...</div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    loadComments();

    function loadComments() {
        $.get("view_comments.php", { project_id: <?= $project_id ?> }, function(data){
            $("#commentsSection").html(data);
        });
    }

    $("#commentForm").submit(function(e){
        e.preventDefault();
        let formData = new FormData(this);

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
                        $("#commentStatus").text(data.message).removeClass('text-danger').addClass('text-success');
                        $("#commentForm")[0].reset();
                        loadComments();
                    } else {
                        $("#commentStatus").text(data.error || "Something went wrong.").removeClass('text-success').addClass('text-danger');
                    }
                } catch(e) {
                    $("#commentStatus").text("Error processing response").removeClass('text-success').addClass('text-danger');
                    console.log(response);
                }
            },
            error: function(xhr, status, error) {
                $("#commentStatus").text("Error: " + error).removeClass('text-success').addClass('text-danger');
            }
        });
    });
});
</script>

</body>
</html>
