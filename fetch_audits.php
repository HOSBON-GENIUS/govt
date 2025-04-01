<?php
require "config.php";

$sql = "SELECT * FROM audit_report";
$stmt = $conn->query($sql);

while ($audit = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$audit['audit_id']}</td>
            <td>{$audit['project_id']}</td>
            <td>{$audit['auditor_id']}</td>
            <td>{$audit['report_text']}</td>
          </tr>";
}
?>
