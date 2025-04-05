<?php
/**
 * Comment Data Fetcher
 * 
 * This script retrieves all comments from the database and formats them
 * as HTML table rows for display in the admin dashboard's comment section.
 * 
 * Connected Pages:
 * - config.php: Database configuration and connection
 * - admin.php: Parent page that displays the comment data
 * 
 * Database Tables:
 * - comment: Contains all comment records including:
 *   * comment_id: Unique identifier for each comment
 *   * project_id: Reference to the associated project
 *   * user_id: Reference to the comment author
 *   * comment_text: The actual comment content
 */

// Initialize database connection
require "config.php";

// Prepare and execute query to fetch all comments
// Retrieves complete comment records for display
$sql = "SELECT * FROM comment";
$stmt = $conn->query($sql);

// Process result set and generate HTML output
// Creates table rows for each comment in the database
while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Generate formatted HTML table row containing:
    // - Unique comment identifier
    // - Associated project reference
    // - Author's user identifier
    // - The comment content
    echo "<tr>
            <td>{$comment['comment_id']}</td>
            <td>{$comment['project_id']}</td>
            <td>{$comment['user_id']}</td>
            <td>{$comment['comment_text']}</td>
          </tr>";
}
?>
