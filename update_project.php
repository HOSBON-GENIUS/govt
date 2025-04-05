<?php
/**
 * Project Update Handler
 * 
 * This script processes project modification requests, including:
 * - Basic project information updates
 * - Media file uploads and storage
 * - Database transaction management
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - edit_project.php: Update form interface
 * - pm_dashboard.php: Project manager view
 * - admin.php: Administrator view
 * 
 * Database Tables:
 * - project: Main project information
 * - project_media: Project-related media files
 */

// Initialize required components
session_start();
require "config.php";

// Verify user authorization
// Only admin and project manager roles can update projects
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ["admin", "project_manager"])) {
    die(json_encode(['error' => 'Unauthorized access']));
}

try {
    // Start database transaction for data integrity
    $conn->beginTransaction();

    // Update core project information
    // Uses prepared statement for SQL injection prevention
    $stmt = $conn->prepare("UPDATE project SET 
        title = ?, description = ?, budget = ?, 
        start_date = ?, end_date = ?, progress = ? 
        WHERE project_id = ?");
    
    // Execute update with form data
    $stmt->execute([
        $_POST['title'],        // Project title
        $_POST['description'],  // Project description
        $_POST['budget'],       // Project budget
        $_POST['start_date'],   // Start date
        $_POST['end_date'],     // End date
        $_POST['progress'],     // Completion percentage
        $_POST['project_id']    // Project identifier
    ]);

    // Process media file uploads
    if (isset($_FILES['project_media']) && !empty($_FILES['project_media']['name'][0])) {
        // Ensure upload directory exists
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Process each uploaded file
        foreach ($_FILES['project_media']['tmp_name'] as $key => $tmp_name) {
            // Generate unique filename
            $file_name = time() . '_' . $_FILES['project_media']['name'][$key];
            $file_path = $upload_dir . $file_name;
            
            // Move file to permanent storage
            if (move_uploaded_file($tmp_name, $file_path)) {
                // Determine file type (image/video)
                $file_type = strpos($_FILES['project_media']['type'][$key], 'image/') === 0 ? 'image' : 'video';
                
                // Record file information in database
                $media_stmt = $conn->prepare("INSERT INTO project_media (project_id, file_type, file_path) VALUES (?, ?, ?)");
                $media_stmt->execute([$_POST['project_id'], $file_type, $file_path]);
            }
        }
    }

    // Commit all changes if successful
    $conn->commit();
    echo json_encode(['message' => 'Project updated successfully']);

} catch (Exception $e) {
    // Rollback changes on error
    $conn->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>