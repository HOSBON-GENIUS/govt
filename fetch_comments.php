<?php
require "config.php";

$sql = "SELECT * FROM comment";
$stmt = $conn->query($sql);

while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$comment['comment_id']}</td>
            <td>{$comment['project_id']}</td>
            <td>{$comment['user_id']}</td>
            <td>{$comment['comment_text']}</td>
          </tr>";
}
?>
