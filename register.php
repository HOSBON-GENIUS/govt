<?php
require "config.php";
include "navbar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"]; // 'citizen', 'project_manager', 'auditor', 'admin'

    $sql = "INSERT INTO user (full_name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$full_name, $email, $password, $role])) {
        echo json_encode(["message" => "User registered successfully!"]);
    } else {
        echo json_encode(["error" => "Registration failed"]);
    }
}
?>
