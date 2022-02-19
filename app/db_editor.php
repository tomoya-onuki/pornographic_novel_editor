<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

$targetID = 0;

// PDOでDBからデータを取得
$stmt = $pdo->prepare('SELECT * FROM dict');
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>データベース編集</title>
</head>
<body>
    


用語：<textarea id="word" cols="30" rows="10"></textarea>
意味：<textarea id="meaning" cols="30" rows="10"></textarea>
用法：<textarea id="ex_sentence" cols="30" rows="10"></textarea>
作者：<textarea id="author" cols="30" rows="10"></textarea>
<button id="db_add_btn">追加</button>

<table border="1">
    <tr>
        <th>用語</th>
        <th>意味</th>
        <th>用法</th>
        <th>作者</th>
    </tr>
    <?php while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $result['word'] . '</td>';
        echo '<td>' . $result['meaning'] . '</td>';
        echo '<td>' . $result['ex_sentence'] . '</td>';
        echo '<td>' . $result['author'] . '</td>';
        echo '</tr>';
        // var_dump($result);
    }
    ?>
</table>
</body>
</html>