<?php
require "config.php";
session_start(); // Add this if not already present

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        // Set session variables
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["full_name"] = $user["full_name"];

        // Redirect to respective dashboard
        if ($user["role"] === "project_manager") {
            header("Location: pm_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        echo json_encode(["error" => "Invalid credentials"]);
    }
}
?>
