<?php
require "config.php";

try {
    // Get total projects
    $stmt = $conn->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN progress = 100 THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN progress < 100 THEN 1 ELSE 0 END) as ongoing,
        SUM(budget) as budget
    FROM project");
    
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ensure all values are numeric and handle nulls
    $stats['total'] = (int)$stats['total'];
    $stats['completed'] = (int)$stats['completed'];
    $stats['ongoing'] = (int)$stats['ongoing'];
    $stats['budget'] = (float)($stats['budget'] ?? 0);

    header('Content-Type: application/json');
    echo json_encode($stats);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error']);
}