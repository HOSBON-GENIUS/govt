<?php
/**
 * Comment Management Handler
 * 
 * This script handles the addition of comments and associated media files to projects.
 * It processes POST requests from the frontend comment form and manages file uploads.
 * 
 * Connected Pages:
 * - config.php: Database configuration and connection
 * - Frontend pages that submit comments (likely project detail/view pages)
 * 
 * Database Tables:
 * - comment: Stores comment data
 * - media: Stores uploaded media files information
 */

// Include database configuration and start user session
require "config.php";
session_start();

// Handle POST request for adding comments
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract and validate input data from POST request and user session
    $project_id = isset($_POST["project_id"]) && is_numeric($_POST["project_id"]) ? (int)$_POST["project_id"] : null;
    $comment_text = isset($_POST["comment_text"]) ? trim($_POST["comment_text"]) : "";
    $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

    // Perform validation checks for required data before proceeding
    if (!$project_id || empty($comment_text) || !$user_id) {
        echo json_encode(["error" => "Missing or invalid project ID, comment, or user not logged in."]);
        exit;
    }

    try {
        // Create new comment record in the database and get its ID
        $stmt = $conn->prepare("INSERT INTO comment (project_id, user_id, comment_text) VALUES (?, ?, ?)");
        if ($stmt->execute([$project_id, $user_id, $comment_text])) {
            $comment_id = $conn->lastInsertId();

            // Process media file upload if one was provided with the comment
            if (isset($_FILES["media"]) && $_FILES["media"]["error"] === 0) {
                $file = $_FILES["media"];
                $upload_dir = "uploads/";
                
                // Create upload directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Generate unique filename and set up file path
                $filename = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($file["name"]));
                $target_path = $upload_dir . $filename;
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Define allowed file types for upload
                $allowed_types = ["jpg", "jpeg", "png", "gif", "mp4", "mov", "avi"];

                // Validate file type
                if (!in_array($ext, $allowed_types)) {
                    throw new Exception("File type not allowed. Allowed types: " . implode(", ", $allowed_types));
                }

                // Move uploaded file to target directory
                if (!move_uploaded_file($file["tmp_name"], $target_path)) {
                    throw new Exception("Failed to move uploaded file. Check directory permissions.");
                }

                // Determine if the file is a video or image
                $file_type = in_array($ext, ["mp4", "mov", "avi"]) ? "video" : "image";

                // Save media information to database
                $stmt = $conn->prepare("INSERT INTO media (comment_id, file_path, file_type, uploaded_at) VALUES (?, ?, ?, NOW())");
                if (!$stmt->execute([$comment_id, $target_path, $file_type])) {
                    throw new Exception("Failed to save media information to database.");
                }
                
                // Prepare media information for response
                $media_info = [
                    'media_id' => $conn->lastInsertId(),
                    'file_path' => $target_path,
                    'file_type' => $file_type,
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];
            }

            // Send success response with comment details and media information if applicable
            echo json_encode([
                "success" => true,
                "message" => "Comment posted successfully.",
                "comment_id" => $comment_id,
                "media" => isset($media_info) ? $media_info : null
            ]);
        } else {
            throw new Exception("Failed to post comment.");
        }
    } catch (Exception $e) {
        // Handle and return any errors that occurred during processing
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
