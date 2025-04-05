<?php
/**
 * Project Comments Display Handler
 * 
 * This script retrieves and displays comments for a specific project,
 * including associated media attachments (images and videos).
 * It generates HTML output for the comments section.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - project.php: Project details page
 * - add_comment.php: Comment submission handler
 * 
 * Database Tables:
 * - comment: Project comments
 * - media: Comment attachments
 * - user: Commenter information
 */

// Initialize database connection
require "config.php";

// Input validation
$project_id = isset($_GET['project_id']) && is_numeric($_GET['project_id']) ? 
              (int)$_GET['project_id'] : 0;  // Sanitize project identifier

// Process valid project requests
if ($project_id) {
    // Construct query for comment retrieval
    // Joins multiple tables for complete comment information
    $stmt = $conn->prepare("
        SELECT 
            c.*,                    -- All comment fields
            m.file_path,            -- Media file location
            m.file_type,            -- Media type (image/video)
            u.full_name            -- Commenter's name
        FROM comment c
        LEFT JOIN media m ON c.comment_id = m.comment_id
        LEFT JOIN user u ON c.user_id = u.user_id
        WHERE c.project_id = ?
        ORDER BY c.created_at DESC  -- Most recent first
    ");
    
    // Execute query with security measures
    $stmt->execute([$project_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for each comment
    foreach ($comments as $comment) {
        // Comment header with user info
        echo '<div class="comment-item mb-4 border-bottom pb-3">';
        echo '<div class="d-flex justify-content-between">';
        echo '<strong>' . htmlspecialchars($comment['full_name']) . '</strong>';  // XSS prevention
        echo '<small class="text-muted">' . $comment['created_at'] . '</small>';
        echo '</div>';
        
        // Comment content with security encoding
        echo '<p class="mb-2">' . nl2br(htmlspecialchars($comment['comment_text'])) . '</p>';
        
        // Media attachment handling
        if (!empty($comment['file_path'])) {
            // Image display logic
            if ($comment['file_type'] === 'image') {
                echo '<div class="media-container mb-2">';
                echo '<img src="' . htmlspecialchars($comment['file_path']) . '" 
                      class="img-fluid rounded" 
                      style="max-width: 300px;" 
                      alt="Comment image">';
                echo '</div>';
            // Video display logic
            } else if ($comment['file_type'] === 'video') {
                echo '<div class="media-container mb-2">';
                echo '<video controls class="img-fluid rounded" style="max-width: 300px;">';
                echo '<source src="' . htmlspecialchars($comment['file_path']) . '" 
                      type="video/mp4">';
                echo 'Your browser does not support the video tag.';
                echo '</video>';
                echo '</div>';
            }
        }
        
        echo '</div>';  // Close comment container
    }
} else {
    // Error handling for invalid requests
    echo '<p class="text-danger">Invalid project ID</p>';
}
?>
