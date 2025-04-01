<?php
session_start();
require "config.php";
require "auth.php";
include "navbar.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_SESSION["role"] === "project_manager") {
    $id = $_POST["project_id"];
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $budget = $_POST["budget"];
    $progress = $_POST["progress"];

    $stmt = $conn->prepare("UPDATE project SET title=?, description=?, budget=?, progress=? WHERE project_id=? AND project_manager_id=?");
    $stmt->execute([$title, $desc, $budget, $progress, $id, $_SESSION["user_id"]]);

    header("Location: pm_dashboard.php");
    exit();
}
?>