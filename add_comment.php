<?php
require "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $project_id = isset($_POST["project_id"]) && is_numeric($_POST["project_id"]) ? (int)$_POST["project_id"] : null;
    $comment_text = isset($_POST["comment_text"]) ? trim($_POST["comment_text"]) : "";
    $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

    // Validate all required data
    if (!$project_id || empty($comment_text) || !$user_id) {
        echo json_encode(["error" => "Missing or invalid project ID, comment, or user not logged in."]);
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
