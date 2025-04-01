<?php include "config.php"; include "navbar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Government Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center text-primary">Government Projects</h2>

    <!-- Search Bar -->
    <input type="text" id="searchProject" class="form-control my-3" placeholder="Search for a project...">

    <!-- Projects List -->
    <div id="projectList" class="row">
        <!-- Projects will be loaded here using AJAX -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    loadProjects();

    // Load projects dynamically
    function loadProjects(query = '') {
        $.ajax({
            url: "view_project.php",
            method: "GET",
            data: { search: query },
            success: function(data) {
                $('#projectList').html(data);
            }
        });
    }

    // Search projects
    $("#searchProject").on("keyup", function() {
        let query = $(this).val();
        loadProjects(query);
    });
});
</script>

</body>
</html>
