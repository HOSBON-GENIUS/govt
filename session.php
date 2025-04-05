<?php
/**
 * Session Authentication Guard
 * 
 * This script verifies user authentication status and manages access control.
 * It prevents unauthorized access to protected pages by redirecting
 * unauthenticated users to the login page.
 */

// Initialize or resume existing session
session_start();

// Verify user authentication status
// Redirects to login page if no valid session exists
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");    // Redirect to authentication page
    exit();                          // Terminate script execution
}

/**
 * Connected Pages and Dependencies:
 * - login.php: Authentication redirect target
 * - All protected pages that require user authentication
 * 
 * Session Variables Used:
 * - user_id: Unique identifier for authenticated user
 * 
 * Security Features:
 * - Session validation
 * - Unauthorized access prevention
 * - Automatic redirection
 */
?>
