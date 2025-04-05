<?php
/**
 * User Login Page
 * 
 * This page provides the login interface for users to access the government projects portal.
 * It handles user authentication and role-based redirections.
 * 
 * Connected Pages:
 * - navbar.php: Navigation component
 * - login.php: Backend authentication handler
 * - register_page.php: New user registration
 * 
 * Features:
 * - Email and password authentication
 * - JWT token-based session management
 * - Role-based access control
 * - Secure form submission
 */
?>
<!DOCTYPE html>
<!-- Include global navigation -->
<?php include "navbar.php"; ?>

<html lang="en">
<head>
    <!-- Document configuration and resource loading -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Bootstrap CSS framework for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Main content container with top margin -->
<div class="container mt-5">
    <h2 class="text-center">User Login</h2>

    <!-- Authentication form -->
    <form id="loginForm">
        <!-- Email input field with validation -->
        <input type="email" id="email" class="form-control my-2" placeholder="Email" required>
        <!-- Password input field with validation -->
        <input type="password" id="password" class="form-control my-2" placeholder="Password" required>
        <!-- Form submission button -->
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- Link to registration page for new users -->
    <p class="text-center mt-3"><a href="register_page.php">Create an account</a></p>
</div>

<!-- JavaScript dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Form submission handler
$("#loginForm").submit(function(e){
    // Prevent traditional form submission
    e.preventDefault();

    // Send AJAX POST request to authentication endpoint
    $.post("login.php", {
        // Get form field values
        email: $("#email").val(),
        password: $("#password").val()
    }, function(response){
        // Process server response
        let data = JSON.parse(response);
        if (data.success) {
            // Store authentication token
            localStorage.setItem("jwt", data.token);
            // Perform role-based redirection
            window.location.href = data.redirect;
        } else {
            // Display authentication error
            alert("Invalid login credentials!");
        }
    });
});
</script>

</body>
</html>
