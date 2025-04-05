<?php
/**
 * Project Creation Handler
 * 
 * This script processes new project submissions, including project details and media files.
 * It handles database transactions and file uploads in a secure manner.
 * 
 * Connected Pages:
 * - config.php: Database connection
 * - Called by add_project_page.php via AJAX
 * 
 * Database Tables:
 * - project: Stores project details
 * - project_media: Stores project-related media files
 * 
 * Features:
 * - Project data insertion
 * - Multiple media file handling
 * - Transaction management
 * - Error handling
 */

// Initialize user session and include database configuration
session_start();
require "config.php";

try {
    // Start database transaction for data integrity
    $conn->beginTransaction();

    // Prepare and execute project insertion query
    // Stores basic project information in the database
    $stmt = $conn->prepare("INSERT INTO project (title, description, budget, start_date, end_date, progress, project_manager_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'],          // Project title
        $_POST['description'],    // Project description
        $_POST['budget'],         // Project budget
        $_POST['start_date'],     // Project start date
        $_POST['end_date'],       // Project end date
        $_POST['progress'],       // Initial progress percentage
        $_SESSION['user_id']      // Current user as project manager
    ]);
    
    // Get the ID of the newly created project
    $project_id = $conn->lastInsertId();

    // Media file processing section
    if (!empty($_FILES['project_media']['name'][0])) {
        // Set up upload directory
        $uploadDir = 'uploads/';
        // Create upload directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Process each uploaded media file
        foreach ($_FILES['project_media']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['project_media']['name'][$key];
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Create unique filename to prevent overwrites
            $unique_filename = uniqid() . '_' . $file_name;
            $file_path = $uploadDir . $unique_filename;
            
            // Move uploaded file to permanent location
            if (move_uploaded_file($tmp_name, $file_path)) {
                // Determine media type based on file extension
                $media_type = in_array($file_type, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'video';
                
                // Record media file information in database
                $media_stmt = $conn->prepare("INSERT INTO project_media (project_id, file_path, file_type, uploaded_at) VALUES (?, ?, ?, NOW())");
                $media_stmt->execute([$project_id, $file_path, $media_type]);
            }
        }
    }

    // Commit the transaction if all operations successful
    $conn->commit();
    echo json_encode(['message' => 'Project created successfully']);

} catch (Exception $e) {
    // Rollback transaction if any operation fails
    $conn->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>
