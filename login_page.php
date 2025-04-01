<!DOCTYPE html><?php include "navbar.php"; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">User Login</h2>

    <form id="loginForm">
        <input type="email" id="email" class="form-control my-2" placeholder="Email" required>
        <input type="password" id="password" class="form-control my-2" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3"><a href="register_page.php">Create an account</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$("#loginForm").submit(function(e){
    e.preventDefault();

    $.post("login.php", {  // Call backend login.php
        email: $("#email").val(),
        password: $("#password").val()
    }, function(response){
        let data = JSON.parse(response);
        if (data.token) {
            localStorage.setItem("jwt", data.token);
            window.location.href = "index.php"; // Redirect after login
        } else {
            alert("Invalid login credentials!");
        }
    });
});
</script>

</body>
</html>
