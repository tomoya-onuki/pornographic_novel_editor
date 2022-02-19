<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ふたりで書く官能小説</title>
</head>

<body class="main1">
    <!-- <a href="./"><h1>ふたりの官能小説(仮)</h1></a> -->
    <div class="edit">
        <?php
        if (!empty($_GET['key'])) {
            // 編集中の文書の情報
            $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
            $stmt->bindParam(':key', $_GET['key'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('SELECT * FROM dict WHERE word = :word');
            $stmt->bindParam(':word', $result['word'], PDO::PARAM_STR);
            $stmt->execute();
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>
        <div class="edit_meaning"><?= $result2['meaning'] ?></div>
        <div class="edit_word"><?= $result['word'] ?></div>
        <div id="script"><?= $result['sentence'] ?></div>
    </div>
</body>

</html>