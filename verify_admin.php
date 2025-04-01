<?php
session_start();
require "vendor/autoload.php";
require "config.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "your_secret_key";

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input["token"])) {
    echo json_encode(["error" => "Token missing"]);
    exit;
}

try {
    $decoded = JWT::decode($input["token"], new Key($secret_key, 'HS256'));
    $user_id = $decoded->user_id;

    // Check user in DB
    $stmt = $conn->prepare("SELECT full_name, role FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user["role"] === "admin") {
        // ✅ Set PHP session
        $_SESSION["user_id"] = $user_id;
        $_SESSION["role"] = "admin";
        $_SESSION["full_name"] = $user["full_name"];
        
        echo json_encode(["role" => "admin"]);
    } else {
        echo json_encode(["role" => "unauthorized"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => "Invalid token"]);
}
