<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "message_board", 3306);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 取得所有留言
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>留言板</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message-card {
            background-color: #fff;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .message-card strong {
            font-size: 18px;
            color: #333;
        }

        .message-card small {
            color: #888;
        }

        .actions a {
            margin-right: 10px;
            color: #007BFF;
            text-decoration: none;
            font-size: 14px;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin: 30px 0;
        }
    </style>
</head>
<body>

<h2>留言板</h2>

<!-- 新增留言表單 -->
<form action="insert.php" method="post">
    <label>名稱：</label>
    <input type="text" name="name" required>
    
    <label>留言：</label>
    <textarea name="content" rows="4" required></textarea>
    
    <input type="submit" value="送出">
</form>

<!-- 顯示留言 -->
<?php while ($row = $result->fetch_assoc()) { ?>
    <div class="message-card">
        <p><strong><?= htmlspecialchars($row['name']) ?></strong> 說：</p>
        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
        <p><small><?= $row['created_at'] ?></small></p>
        <div class="actions">
            <a href="edit.php?id=<?= $row['id'] ?>">編輯</a>
            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('確定要刪除嗎？')">刪除</a>
        </div>
    </div>
<?php } ?>

</body>
</html>
