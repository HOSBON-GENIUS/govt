<?php
require "config.php";

$project_id = isset($_GET['project_id']) && is_numeric($_GET['project_id']) ? (int)$_GET['project_id'] : 0;

if ($project_id) {
    $stmt = $conn->prepare("
        SELECT 
            c.*,
            m.file_path,
            m.file_type
        FROM comment c
        LEFT JOIN media m ON c.comment_id = m.comment_id
        WHERE c.project_id = ?
        ORDER BY c.created_at DESC
    ");
    
    $stmt->execute([$project_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($comments as $comment) {
        echo '<div class="comment-item mb-4 border-bottom pb-3">';
        echo '<div class="d-flex justify-content-between">';
        echo '<strong>User #' . htmlspecialchars($comment['user_id']) . '</strong>';
        echo '<small class="text-muted">' . $comment['created_at'] . '</small>';
        echo '</div>';
        echo '<p class="mb-2">' . nl2br(htmlspecialchars($comment['comment_text'])) . '</p>';
        
        // Display media if exists
        if (!empty($comment['file_path'])) {
            if ($comment['file_type'] === 'image') {
                echo '<div class="media-container mb-2">';
                echo '<img src="' . htmlspecialchars($comment['file_path']) . '" class="img-fluid rounded" style="max-width: 300px;" alt="Comment image">';
                echo '</div>';
            } else if ($comment['file_type'] === 'video') {
                echo '<div class="media-container mb-2">';
                echo '<video controls class="img-fluid rounded" style="max-width: 300px;">';
                echo '<source src="' . htmlspecialchars($comment['file_path']) . '" type="video/mp4">';
                echo 'Your browser does not support the video tag.';
                echo '</video>';
                echo '</div>';
            }
        }
        
        echo '</div>';
    }
} else {
    echo '<p class="text-danger">Invalid project ID</p>';
}
?>
