<?php
/**
 * Logout Handler and Confirmation Page
 * 
 * This page handles user session termination and displays a confirmation message
 * with an automatic redirect countdown. It provides visual feedback for successful
 * logout and options for re-authentication.
 * 
 * Connected Pages:
 * - navbar.php: Navigation component
 * - index.php: Redirect destination
 * - login_page.php: Re-authentication option
 * 
 * Features:
 * - Session termination
 * - Countdown timer
 * - Visual feedback
 * - Automatic redirection
 */

// Load navigation and handle session cleanup
include "navbar.php";
session_start();    // Initialize session handling
session_destroy();  // Remove all session data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document configuration and resource loading -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout - Government Projects</title>
    <!-- Bootstrap framework for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styling for logout interface */
        .logout-container {
            background: rgba(0,0,0,0.8);  /* Semi-transparent background */
            padding: 30px;                /* Internal spacing */
            border-radius: 10px;          /* Rounded corners */
            color: white;                 /* Text color */
            margin-top: 50px;             /* Top spacing */
            text-align: center;           /* Content alignment */
        }
        
        /* Countdown timer display */
        .countdown {
            font-size: 2rem;              /* Large text size */
            color: #ce1126;               /* Emphasis color */
            font-weight: bold;            /* Text weight */
        }
    </style>
</head>
<body>
    <!-- Centered content layout -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logout confirmation interface -->
                <div class="logout-container">
                    <!-- Success icon -->
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    <!-- Confirmation messages -->
                    <h2 class="mt-3">Logged Out Successfully</h2>
                    <p>Thank you for using Government Projects Portal</p>
                    <p>Redirecting in <span id="countdown" class="countdown">5</span> seconds...</p>
                    <!-- Manual login option -->
                    <a href="login_page.php" class="btn btn-outline-light mt-3">Login Again</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Countdown timer implementation
        let seconds = 5;  // Initial countdown value
        const countdownElement = document.getElementById('countdown');
        
        // Timer update function
        const countdown = setInterval(() => {
            seconds--;    // Decrement counter
            countdownElement.textContent = seconds;  // Update display
            
            // Handle countdown completion
            if (seconds <= 0) {
                clearInterval(countdown);  // Stop timer
                window.location.href = 'index.php';  // Redirect to home
            }
        }, 1000);  // Update every second
    </script>
</body>
</html>
