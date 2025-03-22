<?php
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM messages WHERE id = $id");
$row = $result->fetch_assoc();
?>

<h2>編輯留言</h2>
<form action="update.php" method="post">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    名稱：<input type="text" name="name" value="<?= $row['name'] ?>" required><br>
    留言：<br><textarea name="content" rows="4" cols="40" required><?= $row['content'] ?></textarea><br>
    <input type="submit" value="更新">
</form>
