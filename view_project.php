<?php
/**
 * Project List Generator
 * 
 * This script generates HTML cards displaying all projects in the system.
 * It provides a grid layout of project summaries with key information
 * and links to detailed views.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - project.php: Individual project details
 * - index.php: Main display container
 * 
 * Database Tables:
 * - project: Stores project information
 */

// Initialize database connection
require "config.php";

// Retrieve all projects from database
// Orders by creation date for newest first
$sql = "SELECT * FROM project ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate project display cards
foreach ($projects as $p) {
    // Format budget with thousand separators
    $budgetFormatted = number_format($p['budget']);
    
    // Generate responsive card layout
    echo "
        <div class='col-md-6 col-lg-4 mb-4'>          <!-- Responsive grid column -->
            <div class='card shadow-sm h-100'>         <!-- Card container with shadow -->
                <div class='card-body'>
                    <!-- Project header information -->
                    <h5 class='card-title text-primary'>{$p['title']}</h5>
                    <p class='card-text'>{$p['description']}</p>
                    
                    <!-- Project metrics display -->
                    <ul class='list-unstyled small'>
                        <li><strong>📅 Start:</strong> {$p['start_date']}</li>
                        <li><strong>📅 End:</strong> {$p['end_date']}</li>
                        <li><strong>💰 Budget:</strong> Ksh {$budgetFormatted}</li>
                        <li><strong>📈 Progress:</strong> {$p['progress']}%</li>
                    </ul>
                    
                    <!-- Navigation to detailed view -->
                    <a href='project.php?id={$p['project_id']}' 
                       class='btn btn-sm btn-outline-primary'>View Details</a>
                </div>
                
                <!-- Timestamp footer -->
                <div class='card-footer text-muted small'>
                    Posted on " . date("M j, Y", strtotime($p['created_at'])) . "
                </div>
            </div>
        </div>
    ";
}
?>
