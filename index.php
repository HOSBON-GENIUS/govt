<?php include "config.php"; include "navbar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Government Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.9), rgba(0,0,0,0.9)), url('assets/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 15px 0;  /* Reduced from 30px to 15px */
            margin-bottom: 20px; /* Reduced from 30px to 20px */
        }
        .stats-card {
            border-left: 4px solid #006600;
            background: #f8f9fa;
            padding: 10px;  /* Reduced from 20px to 10px */
            margin-bottom: 10px;  /* Reduced from 20px to 10px */
        }
        .stats-card h3 {
            font-size: 1.5rem;  /* Reduced size */
            margin-bottom: 2px;  /* Reduced margin */
        }
        .stats-card p {
            font-size: 0.9rem;  /* Smaller text */
            margin-bottom: 0;
        }
        .stats-card i {
            font-size: 1.2rem;  /* Smaller icons */
            margin-right: 5px;
        }
        .row.mb-4 {
            margin-bottom: 1rem !important;  /* Reduced margin between stats and project list */
        }
        .search-box {
            background: rgba(255,255,255,0.2);
            padding: 8px;  /* Reduced from 15px to 8px */
            border-radius: 10px;
            margin-top: 8px;  /* Reduced from 15px to 8px */
            border: 1px solid white;
        }
        .hero-section h1 {
            font-size: 2rem;  /* Reduced from 2.5rem to 2rem */
            margin-bottom: 5px;  /* Reduced from 10px to 5px */
            color: white;
        }
        .hero-section .lead {
            font-size: 1rem;  /* Reduced from 1.1rem to 1rem */
            margin-bottom: 5px;  /* Reduced from 10px to 5px */
            color: white;
        }
        .search-box input {
            background: rgba(255,255,255,0.9);
            border: none;
            padding: 8px 12px;  /* Reduced padding for input */
        }
        .search-box input::placeholder {
            color: #666;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-4">Government Projects Portal</h1>
                <p class="lead">Track and monitor government initiatives for transparency and progress.</p>
                
                <!-- Search Box -->
                <div class="search-box">
                    <input type="text" id="searchProject" class="form-control form-control-lg" 
                           placeholder="Search for projects...">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-primary">
                    <i class="fas fa-project-diagram"></i> 
                    <span id="totalProjects">0</span>
                </h3>
                <p class="text-muted mb-0">Total Projects</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    <span id="completedProjects">0</span>
                </h3>
                <p class="text-muted mb-0">Completed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-warning">
                    <i class="fas fa-clock"></i> 
                    <span id="ongoingProjects">0</span>
                </h3>
                <p class="text-muted mb-0">Ongoing</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-info">
                    <i class="fas fa-money-bill-wave"></i> 
                    <span id="totalBudget">0</span>
                </h3>
                <p class="text-muted mb-0">Total Budget (KSH)</p>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div id="projectList" class="row">
        <!-- Projects will be loaded here using AJAX -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    loadProjects();
    updateStats();

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

    function updateStats() {
        $.ajax({
            url: "get_stats.php",
            method: "GET",
            dataType: 'json',  // Add this line
            success: function(data) {
                try {
                    if(data.error) {
                        console.error('Stats Error:', data.error);
                        return;
                    }
                    $('#totalProjects').text(data.total);
                    $('#completedProjects').text(data.completed);
                    $('#ongoingProjects').text(data.ongoing);
                    $('#totalBudget').text(data.budget.toLocaleString());
                } catch(e) {
                    console.error('Stats Parse Error:', e);
                    console.log('Raw Response:', data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Stats Ajax Error:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
            }
        });
    }

    $("#searchProject").on("keyup", function() {
        let query = $(this).val();
        loadProjects(query);
    });
});
</script>

</body>
</html>
