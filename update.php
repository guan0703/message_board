<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
$id = $_POST['id'];
$name = $_POST['name'];
$content = $_POST['content'];

// 更新留言內容
$sql = "UPDATE messages SET name=?, content=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $content, $id);
$stmt->execute();

// 修改完跳回首頁
header("Location: index.php");
?>