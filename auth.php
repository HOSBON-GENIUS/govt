<?php
require "session.php";

function checkRole($required_role) {
    if ($_SESSION["role"] !== $required_role) {
        echo json_encode(["error" => "Unauthorized access"]);
        exit();
    }
}
?>
