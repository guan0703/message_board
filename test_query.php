<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
$result = $conn->query("SELECT * FROM messages");
while ($row = $result->fetch_assoc()) {
    echo $row['name'] . "：";
    echo $row['content'] . "<br>";
}
?>
