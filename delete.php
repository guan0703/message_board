<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

$id = $_GET['id'];
$conn->query("DELETE FROM messages WHERE id = $id");

header("Location: index.php");
