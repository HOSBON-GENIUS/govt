<?php
require "config.php";

$project_id = $_GET["project_id"] ?? 0;

$stmt = $conn->prepare("SELECT c.*, u.full_name FROM comment c
                       LEFT JOIN user u ON c.user_id = u.user_id 
                       WHERE c.project_id = ? 
                       ORDER BY c.created_at DESC");
$stmt->execute([$project_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$comments) {
    echo '<div class="text-muted text-center py-3">No comments yet.</div>';
} else {
    foreach ($comments as $comment) {
        $author = $comment['full_name'] ?? "Guest";
        ?>
        <div class="comment-card mb-3 p-3 border rounded">
            <div class="d-flex justify-content-between">
                <strong class="text-primary"><?= htmlspecialchars($author) ?></strong>
                <small class="text-muted"><?= date("M d, Y h:i A", strtotime($comment["created_at"])) ?></small>
            </div>
            <p class="mt-2 mb-2"><?= nl2br(htmlspecialchars($comment["comment_text"])) ?></p>
            
            <?php
            // Get media (if any)
            $stmt2 = $conn->prepare("SELECT * FROM media WHERE comment_id = ?");
            $stmt2->execute([$comment["comment_id"]]);
            $media = $stmt2->fetchAll();
            
            foreach ($media as $file) {
                if ($file["file_type"] === "image") {
                    echo '<img src="' . htmlspecialchars($file["file_path"]) . '" class="img-fluid mt-2 rounded" style="max-width: 300px;">';
                } elseif ($file["file_type"] === "video") {
                    echo '<video controls class="img-fluid mt-2 rounded" style="max-width: 300px;">
                            <source src="' . htmlspecialchars($file["file_path"]) . '" type="video/mp4">
                            Your browser does not support the video tag.
                          </video>';
                }
            }
            ?>
        </div>
        <?php
    }
}
?>
