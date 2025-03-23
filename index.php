<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 處理新增留言
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO messages (name, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $content);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// 處理刪除留言
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// 處理更新留言
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("UPDATE messages SET name = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $content, $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// 查詢留言
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
if (!empty($keyword)) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE name LIKE ? OR content LIKE ? ORDER BY created_at DESC");
    $kw = "%$keyword%";
    $stmt->bind_param("ss", $kw, $kw);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
}

// 如果是要編輯，查出原始資料
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editResult = $stmt->get_result();
    $editData = $editResult->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>一頁式留言板</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f5f5f5; }
        form, .message-card {
            background: #fff; padding: 20px; border-radius: 8px;
            max-width: 500px; margin: 20px auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }
        input[type="text"], textarea {
            width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover { background: #45a049; }
        .actions a { margin-right: 10px; color: #007BFF; text-decoration: none; }
        .actions a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2 style="text-align: center;">留言板</h2>

<!-- 🔍 搜尋表單 -->
<form method="GET" action="index.php" style="text-align:center;">
    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="輸入關鍵字搜尋留言">
    <input type="submit" value="搜尋">
</form>

<!-- 📝 新增 或 編輯 表單 -->
<form method="POST" action="index.php">
    <?php if ($editData): ?>
        <h3>編輯留言</h3>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        名稱：<input type="text" name="name" value="<?= htmlspecialchars($editData['name']) ?>" required>
        留言：<textarea name="content" required><?= htmlspecialchars($editData['content']) ?></textarea>
        <input type="submit" value="更新">
        <a href="index.php">取消</a>
    <?php else: ?>
        <h3>新增留言</h3>
        <input type="hidden" name="action" value="add">
        名稱：<input type="text" name="name" required>
        留言：<textarea name="content" required></textarea>
        <input type="submit" value="送出">
    <?php endif; ?>
</form>

<!-- 🧾 留言清單 -->
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="message-card">
        <p><strong><?= htmlspecialchars($row['name']) ?></strong> 說：</p>
        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
        <p><small><?= $row['created_at'] ?></small></p>
        <div class="actions">
            <a href="?edit=<?= $row['id'] ?>">編輯</a>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('確定要刪除嗎？')">刪除</a>
        </div>
    </div>
<?php endwhile; ?>

</body>
</html>
