<?php
/**
 * Government Projects Portal Homepage
 * 
 * This is the main landing page of the government projects portal.
 * It displays project statistics, search functionality, and a list of all projects.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - navbar.php: Navigation component
 * - view_project.php: Fetches project listings
 * - get_stats.php: Retrieves project statistics
 * 
 * Features:
 * - Real-time project search
 * - Project statistics dashboard
 * - Dynamic project listing
 * - Responsive design
 */

// Include essential components
include "config.php"; 
include "navbar.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document configuration and resource loading -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Government Projects</title>
    <!-- External CSS frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Custom styling for page components */
        
        /* Hero section with semi-transparent overlay */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.9), rgba(0,0,0,0.9)), url('assets/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 15px 0;
            margin-bottom: 20px;
        }

        /* Statistics display cards styling */
        .stats-card {
            border-left: 4px solid #006600;
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
        }

        /* Typography and layout adjustments */
        .stats-card h3 { font-size: 1.5rem; margin-bottom: 2px; }
        .stats-card p { font-size: 0.9rem; margin-bottom: 0; }
        .stats-card i { font-size: 1.2rem; margin-right: 5px; }
        .row.mb-4 { margin-bottom: 1rem !important; }

        /* Search functionality styling */
        .search-box {
            background: rgba(255,255,255,0.2);
            padding: 8px;
            border-radius: 10px;
            margin-top: 8px;
            border: 1px solid white;
        }

        /* Hero section content styling */
        .hero-section h1 { font-size: 2rem; margin-bottom: 5px; color: white; }
        .hero-section .lead { font-size: 1rem; margin-bottom: 5px; color: white; }

        /* Search input field customization */
        .search-box input {
            background: rgba(255,255,255,0.9);
            border: none;
            padding: 8px 12px;
        }
        .search-box input::placeholder { color: #666; }
    </style>
</head>
<body>

<!-- Hero section with search functionality -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-4">Government Projects Portal</h1>
                <p class="lead">Track and monitor government initiatives for transparency and progress.</p>
                
                <!-- Search input field -->
                <div class="search-box">
                    <input type="text" id="searchProject" class="form-control form-control-lg" 
                           placeholder="Search for projects...">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main content container -->
<div class="container">
    <!-- Statistics display section -->
    <div class="row mb-4">
        <!-- Total projects card -->
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-primary">
                    <i class="fas fa-project-diagram"></i> 
                    <span id="totalProjects">0</span>
                </h3>
                <p class="text-muted mb-0">Total Projects</p>
            </div>
        </div>
        <!-- Completed projects card -->
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    <span id="completedProjects">0</span>
                </h3>
                <p class="text-muted mb-0">Completed</p>
            </div>
        </div>
        <!-- Ongoing projects card -->
        <div class="col-md-3">
            <div class="stats-card">
                <h3 class="text-warning">
                    <i class="fas fa-clock"></i> 
                    <span id="ongoingProjects">0</span>
                </h3>
                <p class="text-muted mb-0">Ongoing</p>
            </div>
        </div>
        <!-- Total budget card -->
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

    <!-- Projects list container -->
    <div id="projectList" class="row">
        <!-- Dynamic project content -->
    </div>
</div>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Main application JavaScript
$(document).ready(function(){
    // Initialize page data
    loadProjects();    // Load initial project listing
    updateStats();     // Load initial statistics

    // Project loading function with search capability
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

    // Statistics update function
    function updateStats() {
        $.ajax({
            url: "get_stats.php",
            method: "GET",
            dataType: 'json',
            success: function(data) {
                try {
                    // Error handling
                    if(data.error) {
                        console.error('Stats Error:', data.error);
                        return;
                    }
                    // Update statistics display with formatted numbers
                    $('#totalProjects').text(data.total);
                    $('#completedProjects').text(data.completed);
                    $('#ongoingProjects').text(data.ongoing);
                    $('#totalBudget').text(data.budget.toLocaleString());
                } catch(e) {
                    // Handle JSON parsing errors
                    console.error('Stats Parse Error:', e);
                    console.log('Raw Response:', data);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX request failures
                console.error('Stats Ajax Error:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
            }
        });
    }

    // Real-time search implementation
    $("#searchProject").on("keyup", function() {
        let query = $(this).val();
        loadProjects(query);
    });
});
</script>

</body>
</html>
