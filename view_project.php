<?php
require "config.php";

$sql = "SELECT * FROM project ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($projects as $p) {
  $budgetFormatted = number_format($p['budget']);
  echo "
    <div class='col-md-6 col-lg-4 mb-4'>
      <div class='card shadow-sm h-100'>
        <div class='card-body'>
          <h5 class='card-title text-primary'>{$p['title']}</h5>
          <p class='card-text'>{$p['description']}</p>
          <ul class='list-unstyled small'>
            <li><strong>📅 Start:</strong> {$p['start_date']}</li>
            <li><strong>📅 End:</strong> {$p['end_date']}</li>
            <li><strong>💰 Budget:</strong> Ksh {$budgetFormatted}</li>
            <li><strong>📈 Progress:</strong> {$p['progress']}%</li>
          </ul>
          <a href='project.php?id={$p['project_id']}' class='btn btn-sm btn-outline-primary'>View Details</a>
        </div>
        <div class='card-footer text-muted small'>
          Posted on " . date("M j, Y", strtotime($p['created_at'])) . "
        </div>
      </div>
    </div>
  ";
}
?>
