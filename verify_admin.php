<?php
/**
 * Admin Authentication Verifier
 * 
 * This script validates administrative access using JWT tokens and manages
 * admin sessions. It provides secure authentication for admin-only operations.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - admin.php: Admin dashboard
 * - login.php: Authentication handler
 * 
 * Dependencies:
 * - Firebase JWT library
 * - PDO database connection
 * 
 * Security Features:
 * - JWT token validation
 * - Role-based access control
 * - Session management
 */

// Initialize core components
session_start();                 // Start session handling
require "vendor/autoload.php";   // Load Composer dependencies
require "config.php";            // Database configuration

// Import JWT handling classes
use Firebase\JWT\JWT;           // JWT generation/validation
use Firebase\JWT\Key;           // JWT key management

// Security configuration
$secret_key = "your_secret_key";  // JWT encryption key

// Parse incoming JSON request
$input = json_decode(file_get_contents("php://input"), true);

// Validate token presence
if (!isset($input["token"])) {
    echo json_encode(["error" => "Token missing"]);
    exit;
}

try {
    // Validate and decode JWT token
    $decoded = JWT::decode($input["token"], new Key($secret_key, 'HS256'));
    $user_id = $decoded->user_id;  // Extract user identifier

    // Verify admin credentials in database
    $stmt = $conn->prepare("SELECT full_name, role FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate admin role and establish session
    if ($user && $user["role"] === "admin") {
        // Set administrative session variables
        $_SESSION["user_id"] = $user_id;         // User identifier
        $_SESSION["role"] = "admin";             // Administrative role
        $_SESSION["full_name"] = $user["full_name"];  // User display name
        
        // Return successful authentication
        echo json_encode(["role" => "admin"]);
    } else {
        // Return access denied response
        echo json_encode(["role" => "unauthorized"]);
    }

} catch (Exception $e) {
    // Handle token validation failures
    echo json_encode(["error" => "Invalid token"]);
}
