<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
$id = $_POST['id'];
$name = $_POST['name'];
$content = $_POST['content'];

$sql = "UPDATE messages SET name=?, content=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $content, $id);
$stmt->execute();

header("Location: index.php");
