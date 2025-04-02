<?php
session_start();
require "config.php"; include "navbar.php";


// Only allow project managers
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "project_manager") {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $budget = $_POST["budget"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $progress = $_POST["progress"];
    $manager_id = $_SESSION["user_id"];

    $sql = "INSERT INTO project (title, description, budget, start_date, end_date, progress, project_manager_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$title, $description, $budget, $start_date, $end_date, $progress, $manager_id])) {
        echo json_encode(["message" => "Project added successfully."]);
    } else {
        echo json_encode(["error" => "Project creation failed."]);
    }
}
?>
