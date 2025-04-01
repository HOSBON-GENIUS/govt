<?php
require "config.php";
echo json_encode($conn->query("SELECT * FROM audit_report")->fetchAll(PDO::FETCH_ASSOC));
?>
