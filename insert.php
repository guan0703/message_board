<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

$name = $_POST['name'];
$content = $_POST['content'];

$sql = "INSERT INTO messages (name, content) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $content);
$stmt->execute();

header("Location: index.php");
