<?php
require "config.php";

$sql = "SELECT * FROM user";
$stmt = $conn->query($sql);

while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$user['user_id']}</td>
            <td>{$user['full_name']}</td>
            <td>{$user['email']}</td>
            <td>{$user['role']}</td>
            <td><button class='btn btn-danger btn-sm' onclick='deleteUser({$user['user_id']})'>Delete</button></td>
          </tr>";
}
?>
