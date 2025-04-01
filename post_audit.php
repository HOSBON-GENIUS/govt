<?php
require "config.php";
require "auth.php";
include "navbar.php";


checkRole("auditor");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST["project_id"];
    $report_text = $_POST["report_text"];
    $flagged_issue = $_POST["flagged_issue"];

    $sql = "INSERT INTO audit_report (project_id, auditor_id, report_text, flagged_issue) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    echo json_encode($stmt->execute([$project_id, $_SESSION["user_id"], $report_text, $flagged_issue]) ? 
        ["message" => "Audit posted!"] : 
        ["error" => "Audit failed"]);
}
?>
