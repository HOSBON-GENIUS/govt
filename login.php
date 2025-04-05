<?php
/**
 * Authentication Handler
 * 
 * This script processes user login requests and manages session creation.
 * It verifies credentials, establishes user sessions, and handles role-based redirections.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - login_page.php: Frontend login interface
 * - pm_dashboard.php: Project manager redirect destination
 * - index.php: Default redirect destination
 * 
 * Security Features:
 * - Password hashing verification
 * - Session-based authentication
 * - Role-based access control
 */

// Initialize required components
require "config.php";
session_start();

// Validate request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize login credentials
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Query database for user account
    // Uses prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Authenticate user credentials
    if ($user && password_verify($password, $user["password"])) {
        // Establish user session with security details
        $_SESSION["user_id"] = $user["user_id"];       // Unique user identifier
        $_SESSION["role"] = $user["role"];             // User's system role
        $_SESSION["full_name"] = $user["full_name"];   // User's display name

        // Prepare successful authentication response
        $response = [
            "success" => true,
            "token" => session_id(),    // Session identifier for client-side storage
            "role" => $user["role"],    // Role information for frontend
            "redirect" => $user["role"] === "project_manager" ? 
                        "pm_dashboard.php" :    // Project manager specific page
                        "index.php"             // Default landing page
        ];
        echo json_encode($response);
    } else {
        // Handle authentication failure
        echo json_encode(["error" => "Invalid credentials"]);
    }
}
?>
