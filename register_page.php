<!DOCTYPE html> <?php include "navbar.php"; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">User Registration</h2>

    <form id="registerForm">
        <input type="text" id="full_name" class="form-control my-2" placeholder="Full Name" required>
        <input type="email" id="email" class="form-control my-2" placeholder="Email" required>
        <input type="password" id="password" class="form-control my-2" placeholder="Password" required>
        <select id="role" class="form-control my-2">
            <option value="citizen">Citizen</option>
            <option value="project_manager">Project Manager</option>
            <option value="auditor">Auditor</option>
        </select>
        <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <p class="text-center mt-3"><a href="login_page.php">Already have an account? Login</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$("#registerForm").submit(function(e){
    e.preventDefault();

    $.post("register.php", {  // Call backend register.php
        full_name: $("#full_name").val(),
        email: $("#email").val(),
        password: $("#password").val(),
        role: $("#role").val()
    }, function(response){
        let data = JSON.parse(response);
        alert(data.message);
        window.location.href = "login_page.php"; // Redirect to login after signup
    });
});
</script>

</body>
</html>
