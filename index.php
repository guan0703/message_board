<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 取得所有留言
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<h2>留言板</h2>

<!-- 新增留言表單 -->
<form action="insert.php" method="post">
    名稱：<input type="text" name="name" required><br>
    留言：<br><textarea name="content" rows="4" cols="40" required></textarea><br>
    <input type="submit" value="送出">
</form>

<hr>

<!-- 顯示留言 -->
<?php while ($row = $result->fetch_assoc()) { ?>
    <p><strong><?= htmlspecialchars($row['name']) ?></strong> 說：</p>
    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
    <p><small><?= $row['created_at'] ?></small></p>
    <a href="edit.php?id=<?= $row['id'] ?>">編輯</a>
    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('確定要刪除嗎？')"> 刪除</a>
    <hr>
<?php } ?>
