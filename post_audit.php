<?php
/**
 * Audit Report Submission Handler
 * 
 * This script processes audit report submissions from authorized auditors.
 * It validates user permissions and stores audit reports in the database.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - auth.php: Authentication utilities
 * - navbar.php: Navigation component
 * - auditor_dashboard.php: Main auditor interface
 * 
 * Database Tables:
 * - audit_report: Stores audit findings and issues
 * 
 * Security:
 * - Role-based access (Auditor only)
 * - Session validation
 * - Prepared statements for SQL
 */

// Initialize required components and dependencies
require "config.php";     // Database connection
require "auth.php";       // Authentication functions
include "navbar.php";     // Navigation interface

// Validate user has auditor privileges
checkRole("auditor");     // Terminates if unauthorized

// Process audit report submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize audit data from form
    $project_id = $_POST["project_id"];       // Project being audited
    $report_text = $_POST["report_text"];     // Audit findings
    $flagged_issue = $_POST["flagged_issue"]; // Issue indicators

    // Prepare database insertion query
    // Uses parameterized query for security
    $sql = "INSERT INTO audit_report (project_id, auditor_id, report_text, flagged_issue) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Execute query and generate response
    // Returns JSON formatted success/error message
    echo json_encode($stmt->execute([
        $project_id,              // Project identifier
        $_SESSION["user_id"],     // Current auditor's ID
        $report_text,            // Audit report content
        $flagged_issue           // Issue flag status
    ]) ? 
        ["message" => "Audit posted!"] :    // Success response
        ["error" => "Audit failed"]         // Error response
    );
}
?>
