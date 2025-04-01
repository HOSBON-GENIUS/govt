<?php
require "config.php";
session_start();

file_put_contents("debug.log", print_r($_POST, true));


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $project_id = isset($_POST["project_id"]) && is_numeric($_POST["project_id"]) ? (int)$_POST["project_id"] : null;

if (!$project_id || empty($comment_text)) {
    echo json_encode(["error" => "Missing or invalid project ID or comment."]);
    exit;
}


    // Insert the comment first
    $stmt = $conn->prepare("INSERT INTO comment (project_id, user_id, comment_text) VALUES (?, ?, ?)");
    if ($stmt->execute([$project_id, $user_id, $comment_text])) {
        $comment_id = $conn->lastInsertId();

        // Handle file upload (optional)
        if (isset($_FILES["media"]) && $_FILES["media"]["error"] === 0) {
            $file = $_FILES["media"];
            $upload_dir = "uploads/";
            $filename = time() . "_" . basename($file["name"]);
            $target_path = $upload_dir . $filename;
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $allowed_types = ["jpg", "jpeg", "png", "gif", "mp4", "mov", "avi"];

            if (in_array($ext, $allowed_types)) {
                if (move_uploaded_file($file["tmp_name"], $target_path)) {
                    $file_type = in_array($ext, ["mp4", "mov", "avi"]) ? "video" : "image";

                    // Save media reference
                    $stmt = $conn->prepare("INSERT INTO media (comment_id, file_path, file_type) VALUES (?, ?, ?)");
                    $stmt->execute([$comment_id, $target_path, $file_type]);
                }
            }
        }

        echo json_encode(["message" => "Comment posted successfully."]);
    } else {
        echo json_encode(["error" => "Failed to post comment."]);
    }
}
?>
