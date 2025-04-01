<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "project_manager") {
    header("Location: login_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Project</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h3 class="text-center text-success">Post a New Project</h3>

  <form id="projectForm">
    <input type="text" id="title" class="form-control my-2" placeholder="Project Title" required>
    <textarea id="description" class="form-control my-2" placeholder="Project Description" required></textarea>
    <input type="number" id="budget" class="form-control my-2" placeholder="Budget (KES)" required>
    <input type="date" id="start_date" class="form-control my-2" required>
    <input type="date" id="end_date" class="form-control my-2" required>
    <button type="submit" class="btn btn-primary w-100">Add Project</button>
  </form>

  <div id="result" class="mt-3 text-center"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$("#projectForm").submit(function(e){
  e.preventDefault();

  $.post("create_project.php", {
    title: $("#title").val(),
    description: $("#description").val(),
    budget: $("#budget").val(),
    start_date: $("#start_date").val(),
    end_date: $("#end_date").val()
  }, function(response){
    let data = JSON.parse(response);
    if (data.message) {
      $("#result").html(`<span class="text-success">${data.message}</span>`);
      $("#projectForm")[0].reset();
    } else {
      $("#result").html(`<span class="text-danger">${data.error || 'Something went wrong.'}</span>`);
    }
  });
});
</script>

</body>
</html>
