<?php
require "session.php";

function checkRole($required_role) {
    if ($_SESSION["role"] !== $required_role) {
        echo json_encode(["error" => "Unauthorized access"]);
        exit();
    }
}

/**
 * Authentication Helper Module
 * 
 * This file provides role-based access control functionality for the application.
 * The checkRole function validates if the current user has the required role
 * to access specific features or pages.
 * 
 * Connected Pages:
 * - session.php: Required for session management
 * - Used by various pages that need role-based access control
 * 
 * Function Explanation:
 * - checkRole($required_role): 
 *   - Takes a role parameter (e.g., 'admin', 'project_manager')
 *   - Compares it with the user's current role stored in session
 *   - Returns JSON error and exits if roles don't match
 *   - Silently continues execution if roles match
 */
?>
