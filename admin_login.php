<?php
/**
 * Admin Login Page
 * 
 * This page provides the admin login interface with JWT-based authentication.
 * It validates admin credentials and ensures only users with admin role can access
 * the admin dashboard.
 * 
 * Connected Pages:
 * - navbar.php: Navigation component
 * - login.php: Backend authentication handler
 * - verify_admin.php: Admin role verification endpoint
 * - admin.php: Admin dashboard (redirect after successful login)
 * 
 * Authentication Flow:
 * 1. User submits credentials
 * 2. Backend validates and returns JWT token
 * 3. Token is verified for admin role
 * 4. Redirects to admin dashboard if authorized
 */
?>
<!DOCTYPE html>
<!-- Include the global navigation component -->
<?php include "navbar.php"; ?>

<html lang="en">
<head>
    <!-- Define character encoding for proper text rendering -->
    <meta charset="UTF-8">
    <!-- Set page title in browser tab -->
    <title>Admin Login</title>
    <!-- Configure viewport for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Include Bootstrap CSS framework for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Main content container with top margin -->
<div class="container mt-5">
    <!-- Center-aligned row for login form -->
    <div class="row justify-content-center">
        <!-- Column with responsive width for form container -->
        <div class="col-md-5">
            <!-- Page heading -->
            <h3 class="text-center text-primary">Admin Login</h3>
            <!-- Login form with email and password inputs -->
            <form id="adminLoginForm">
                <!-- Email input field -->
                <input type="email" id="email" class="form-control my-3" placeholder="Admin Email" required>
                <!-- Password input field -->
                <input type="password" id="password" class="form-control my-3" placeholder="Password" required>
                <!-- Submit button spanning full width -->
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <!-- Container for displaying error messages -->
            <div id="errorMsg" class="text-danger mt-3 text-center"></div>
        </div>
    </div>
</div>

<!-- Include jQuery for AJAX functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Add submit event listener to the login form
$("#adminLoginForm").submit(function(e) {
    // Prevent the form from submitting traditionally
    e.preventDefault();

    // Send AJAX POST request to login endpoint
    $.post("login.php", {
        // Get values from form inputs
        email: $("#email").val(),
        password: $("#password").val()
    }, function(response) {
        // Parse JSON response from server
        let data = JSON.parse(response);

        // Check if authentication was successful (token received)
        if (data.token) {
            // Store the JWT token in browser's local storage
            localStorage.setItem("jwt", data.token);

            // Make secondary request to verify admin privileges
            fetch("verify_admin.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ token: data.token })
            })
            .then(res => res.json())
            .then(result => {
                // Check if user has admin role
                if (result.role === "admin") {
                    // Redirect to admin dashboard if authorized
                    window.location.href = "admin.php";
                } else {
                    // Display access denied message for non-admin users
                    $("#errorMsg").text("Access denied. Admins only.");
                }
            });
        } else {
            // Display error message if login failed
            $("#errorMsg").text(data.error || "Login failed.");
        }
    });
});
</script>

</body>
</html>
