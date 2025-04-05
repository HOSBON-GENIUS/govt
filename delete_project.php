<?php
/**
 * Project Deletion Handler
 * 
 * This script handles the secure deletion of projects and their associated data.
 * It removes project records, related media files, and comments while maintaining
 * data integrity through transaction management.
 * 
 * Connected Pages:
 * - config.php: Database connection
 * - Called by admin.php via AJAX
 * 
 * Database Tables Affected:
 * - project: Main project record
 * - media: Project media files
 * - comment: Project comments
 * 
 * Security: Restricted to admin users only
 */

// Initialize session and database connection
session_start();
require "config.php";

// Verify admin privileges
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die(json_encode(['error' => 'Unauthorized access']));
}

try {
    // Validate and sanitize project ID from POST request
    $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
    if (!$project_id) {
        throw new Exception('Invalid project ID');
    }

    // Start transaction for maintaining data consistency
    $conn->beginTransaction();

    // Retrieve and delete physical media files
    $media_stmt = $conn->prepare("SELECT file_path FROM media WHERE project_id = ?");
    $media_stmt->execute([$project_id]);
    while ($media = $media_stmt->fetch()) {
        // Remove physical files from server if they exist
        if (file_exists($media['file_path'])) {
            unlink($media['file_path']); // Delete file from filesystem
        }
    }

    // Delete all related records in proper order to maintain referential integrity
    // Remove media records
    $conn->prepare("DELETE FROM media WHERE project_id = ?")->execute([$project_id]);
    // Remove comment records
    $conn->prepare("DELETE FROM comment WHERE project_id = ?")->execute([$project_id]);
    // Remove the project record itself
    $conn->prepare("DELETE FROM project WHERE project_id = ?")->execute([$project_id]);

    // Commit all deletion operations
    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Rollback all operations if any step fails
    $conn->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>