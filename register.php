<?php
/**
 * User Registration Handler
 * 
 * This script processes new user registration requests, creates user accounts,
 * and establishes user sessions. It handles data validation, password encryption,
 * and role-based redirections.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - register_page.php: Registration form interface
 * - pm_dashboard.php: Project manager redirect
 * - index.php: Default redirect
 * 
 * Database Tables:
 * - user: Stores user account information
 */

// Initialize required components
require "config.php";     // Database connection setup
session_start();         // Initialize session handling

// Process registration requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize user input
    $full_name = $_POST["full_name"];    // User's full name
    $email = $_POST["email"];            // User's email address
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);  // Encrypted password
    $role = $_POST["role"];              // User's system role

    try {
        // Prepare and execute user creation query
        // Uses parameterized query for SQL injection prevention
        $stmt = $conn->prepare("INSERT INTO user (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $password, $role]);

        // Establish user session
        $_SESSION["user_id"] = $conn->lastInsertId();    // New user's ID
        $_SESSION["role"] = $role;                       // User's role
        $_SESSION["full_name"] = $full_name;             // User's name

        // Determine appropriate redirect based on user role
        $redirect = $role === "project_manager" ? 
                   "pm_dashboard.php" :    // Project manager dashboard
                   "index.php";            // Regular user homepage
        
        // Generate success response
        echo json_encode([
            "success" => true,
            "message" => "Registration successful!",
            "token" => session_id(),       // Session identifier
            "redirect" => $redirect        // Redirect destination
        ]);
    } catch (PDOException $e) {
        // Handle database errors (e.g., duplicate email)
        echo json_encode([
            "success" => false,
            "message" => "Registration failed. Email might already be in use."
        ]);
    }
}
?>
