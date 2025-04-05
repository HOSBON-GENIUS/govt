<?php
/**
 * User Data Fetcher for Admin Panel
 * 
 * This script retrieves all user accounts from the database and formats them
 * as HTML table rows for display in the admin dashboard. It includes user
 * details and management controls for each user account.
 * 
 * Connected Pages:
 * - config.php: Database configuration and connection
 * - admin.php: Parent page that displays the user list
 * - delete_user.php: Handles user deletion requests
 * 
 * Database Tables:
 * - user: Contains all user account information
 */

// Establish database connection
require "config.php";

// Prepare and execute query to retrieve all user accounts
// Returns complete user records for administrative display
$sql = "SELECT * FROM user";
$stmt = $conn->query($sql);

// Process each user record and generate formatted HTML output
while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Generate HTML table row containing:
    // - Unique user identifier
    // - User's full name
    // - User's email address
    // - Assigned system role
    // - Action button for account management
    echo "<tr>
            <td>{$user['user_id']}</td>
            <td>{$user['full_name']}</td>
            <td>{$user['email']}</td>
            <td>{$user['role']}</td>
            <td><button class='btn btn-danger btn-sm' onclick='deleteUser({$user['user_id']})'>Delete</button></td>
          </tr>";
}
?>
