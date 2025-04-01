<?php
require "config.php";

$sql = "SELECT * FROM project";
$stmt = $conn->query($sql);

while ($project = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$project['project_id']}</td>
            <td>{$project['title']}</td>
            <td>Ksh {$project['budget']}</td>
            <td>{$project['progress']}%</td>
            <td><button class='btn btn-danger btn-sm' onclick='deleteProject({$project['project_id']})'>Delete</button></td>
          </tr>";
}
?>
