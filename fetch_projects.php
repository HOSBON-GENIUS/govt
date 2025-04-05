<?php
/**
 * Project Data Fetcher for Admin Panel
 * 
 * This script retrieves all projects from the database and formats them
 * as HTML table rows for display in the admin dashboard. It includes
 * project details and management buttons for each project.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - admin.php: Parent page that displays the project list
 * - edit_project.php: Link destination for project editing
 * - delete_project.php: Handles project deletion requests
 * 
 * Security: Restricted to admin users only
 */

// Initialize user session and database connection
session_start();
require "config.php";

// Verify user has admin privileges
// Terminates script if user is not authenticated or lacks admin role
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Unauthorized access");
}

// Retrieve all projects from database, ordered by newest first
$stmt = $conn->query("SELECT * FROM project ORDER BY project_id DESC");

// Generate HTML table rows for each project
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Start new table row
    echo "<tr>";
    
    // Output project identifier
    echo "<td>" . htmlspecialchars($row['project_id']) . "</td>";
    
    // Output project title with XSS protection
    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
    
    // Output formatted budget with currency symbol
    echo "<td>Ksh " . number_format($row['budget']) . "</td>";
    
    // Output project progress as percentage
    echo "<td>" . htmlspecialchars($row['progress']) . "%</td>";
    
    // Generate action buttons for project management
    // - Edit button links to edit page
    // - Delete button triggers JavaScript deletion function
    echo "<td>
            <a href='edit_project.php?id=" . $row['project_id'] . "' class='btn btn-primary btn-sm'>Edit</a>
            <button onclick='deleteProject(" . $row['project_id'] . ")' class='btn btn-danger btn-sm'>Delete</button>
          </td>";
    
    // Close table row
    echo "</tr>";
}
?>
