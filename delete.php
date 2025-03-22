<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 取得 id 並轉成整數（加強安全）
$id = intval($_GET['id']);

// 使用 prepare + bind_param（安全做法）
$sql = "DELETE FROM messages WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);  // i 表示整數
$stmt->execute();

// 回首頁
header("Location: index.php");
?>
