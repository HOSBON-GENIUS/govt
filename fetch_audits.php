<?php
/**
 * Audit Report Data Fetcher
 * 
 * This script retrieves and formats audit report data for display in the admin dashboard.
 * It generates HTML table rows containing audit information for each report in the database.
 * 
 * Connected Pages:
 * - config.php: Database configuration and connection
 * - admin.php: Parent page that displays the audit data
 * 
 * Database Tables:
 * - audit_report: Contains all audit records including:
 *   * audit_id: Unique identifier for each audit
 *   * project_id: Reference to the audited project
 *   * auditor_id: Reference to the user who performed the audit
 *   * report_text: Detailed audit findings
 */

// Establish database connection through configuration file
require "config.php";

// Execute database query to retrieve all audit reports
// Returns all columns from audit_report table for display
$sql = "SELECT * FROM audit_report";
$stmt = $conn->query($sql);

// Generate HTML table rows for each audit record
// Each iteration creates a formatted row with audit details
while ($audit = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Format and output table row with audit information
    // Each cell contains specific audit data fields
    echo "<tr>
            <td>{$audit['audit_id']}</td>
            <td>{$audit['project_id']}</td>
            <td>{$audit['auditor_id']}</td>
            <td>{$audit['report_text']}</td>
          </tr>";
}
?>
