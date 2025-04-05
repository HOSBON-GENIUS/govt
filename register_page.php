<?php
/**
 * User Registration Interface
 * 
 * This page provides the registration form for new users to create accounts
 * in the government projects portal. It supports multiple user roles and
 * handles form submission via AJAX.
 * 
 * Connected Pages:
 * - navbar.php: Navigation component
 * - register.php: Backend registration handler
 * - login_page.php: Alternative authentication option
 * 
 * User Roles Available:
 * - Citizen: Regular user access
 * - Project Manager: Project creation and management
 * - Auditor: Project auditing capabilities
 */
?>
<!DOCTYPE html>
<?php include "navbar.php"; ?>

<html lang="en">
<head>
    <!-- Document configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <!-- Bootstrap framework for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Registration interface container -->
<div class="container mt-5">
    <h2 class="text-center">User Registration</h2>

    <!-- User registration form -->
    <form id="registerForm">
        <!-- Personal information inputs -->
        <input type="text" id="full_name" class="form-control my-2" 
               placeholder="Full Name" required>
        <input type="email" id="email" class="form-control my-2" 
               placeholder="Email" required>
        <input type="password" id="password" class="form-control my-2" 
               placeholder="Password" required>
        
        <!-- User role selection -->
        <select id="role" class="form-control my-2">
            <option value="citizen">Citizen</option>
            <option value="project_manager">Project Manager</option>
            <option value="auditor">Auditor</option>
        </select>
        
        <!-- Form submission button -->
        <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <!-- Authentication alternative -->
    <p class="text-center mt-3">
        <a href="login_page.php">Already have an account? Login</a>
    </p>
</div>

<!-- JavaScript dependencies and functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Registration form submission handler
$("#registerForm").submit(function(e){
    // Prevent standard form submission
    e.preventDefault();

    // Send registration request to backend
    $.post("register.php", {
        // Collect form field values
        full_name: $("#full_name").val(),
        email: $("#email").val(),
        password: $("#password").val(),
        role: $("#role").val()
    }, function(response){
        // Process server response
        let data = JSON.parse(response);
        
        // Handle registration outcome
        if (data.success) {
            // Display success message and redirect
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            // Display error message
            alert(data.message || "Registration failed!");
        }
    });
});
</script>

</body>
</html>
