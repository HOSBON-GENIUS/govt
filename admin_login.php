<!DOCTYPE html><?php include "navbar.php"; ?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <h3 class="text-center text-primary">Admin Login</h3>
      <form id="adminLoginForm">
        <input type="email" id="email" class="form-control my-3" placeholder="Admin Email" required>
        <input type="password" id="password" class="form-control my-3" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <div id="errorMsg" class="text-danger mt-3 text-center"></div>
    </div>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$("#adminLoginForm").submit(function(e) {
  e.preventDefault();

  $.post("login.php", {
    email: $("#email").val(),
    password: $("#password").val()
  }, function(response) {
    let data = JSON.parse(response);

    if (data.token) {
      // Decode JWT to check role (optional; for quick redirect we’ll skip decoding)
      localStorage.setItem("jwt", data.token);

      // Send token to backend for role check (optional)
      fetch("verify_admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: data.token })
      })
      .then(res => res.json())
      .then(result => {
        if (result.role === "admin") {
          window.location.href = "admin.php";
        } else {
          $("#errorMsg").text("Access denied. Admins only.");
        }
      });
    } else {
      $("#errorMsg").text(data.error || "Login failed.");
    }
  });
});
</script>

</body>
</html>
