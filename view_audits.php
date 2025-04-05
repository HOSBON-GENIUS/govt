<?php
/**
 * Audit Reports Retrieval Handler
 * 
 * This script fetches all audit reports from the database and returns them
 * in JSON format for display in the audit interface.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - auditor_dashboard.php: Audit display interface
 * - project.php: Project details page
 * 
 * Database Tables:
 * - audit_report: Stores project audit information
 * 
 * Output Format:
 * - JSON array of audit reports
 * - Each report contains audit details and findings
 */

// Initialize database connection
require "config.php";

// Retrieve and format all audit reports
// Converts database results to JSON format for frontend consumption
echo json_encode(
    $conn->query("SELECT * FROM audit_report")  // Fetch all audit records
         ->fetchAll(PDO::FETCH_ASSOC)           // Convert to associative array
);
?>
