require "config.php";

$project_id = $_GET["project_id"] ?? 0;

$stmt = $conn->prepare("SELECT c.*, u.full_name FROM comment c
                        LEFT JOIN user u ON c.user_id = u.user_id
                        WHERE c.project_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$project_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$comments) {
    echo "<div class='text-muted'>No comments yet.</div>";
}

foreach ($comments as $comment) {
    $author = $comment['full_name'] ?? "Guest";

    echo "<div class='border-bottom pb-3 mb-3'>
      <strong>{$author}:</strong><br>
      " . nl2br(htmlspecialchars($comment["comment_text"])) . "
      <div class='text-muted small'>" . date("M d, Y h:i A", strtotime($comment["created_at"])) . "</div>";

    // Get media (if any)
    $stmt2 = $conn->prepare("SELECT * FROM media WHERE comment_id = ?");
    $stmt2->execute([$comment["comment_id"]]);
    $media = $stmt2->fetchAll();

    foreach ($media as $file) {
        if ($file["file_type"] === "image") {
            echo "<div class='mt-2'><img src='{$file['file_path']}' class='img-fluid rounded' style='max-height:250px'></div>";
        } elseif ($file["file_type"] === "video") {
            echo "<div class='mt-2'><video controls style='max-width:100%; height:auto;'><source src='{$file['file_path']}'></video></div>";
        }
    }

    echo "</div>";
}
