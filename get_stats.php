<?php
/**
 * Project Statistics Generator
 * 
 * This script calculates and returns key project statistics in JSON format.
 * It provides metrics about project completion status and financial overview.
 * 
 * Connected Pages:
 * - config.php: Database configuration
 * - dashboard.php: Displays these statistics
 * - index.php: May display summary statistics
 * 
 * Output Format: JSON containing:
 * - total: Total number of projects
 * - completed: Number of finished projects
 * - ongoing: Number of active projects
 * - budget: Total budget allocation
 */

// Initialize database connection
require "config.php";

try {
    // Execute complex SQL query to calculate project statistics
    // Uses COUNT and SUM with CASE statements for different metrics
    $stmt = $conn->query("SELECT 
        COUNT(*) as total,                                          -- Count all projects
        SUM(CASE WHEN progress = 100 THEN 1 ELSE 0 END) as completed,  -- Count completed projects
        SUM(CASE WHEN progress < 100 THEN 1 ELSE 0 END) as ongoing,    -- Count ongoing projects
        SUM(budget) as budget                                          -- Calculate total budget
    FROM project");
    
    // Retrieve the calculated statistics
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Convert string results to appropriate data types
    // Ensures consistent data types in JSON output
    $stats['total'] = (int)$stats['total'];           // Convert to integer
    $stats['completed'] = (int)$stats['completed'];   // Convert to integer
    $stats['ongoing'] = (int)$stats['ongoing'];       // Convert to integer
    $stats['budget'] = (float)($stats['budget'] ?? 0); // Convert to float, default to 0 if null
    
    // Set response header to JSON and output data
    header('Content-Type: application/json');
    echo json_encode($stats);

} catch (PDOException $e) {
    // Error handling for database operations
    // Returns a JSON error response without exposing database details
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error']);
}