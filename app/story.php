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
    <title>ふたりの官能小説(仮)</title>
</head>

<body>
    <h1>ふたりの官能小説(仮)</h1>
<?php


if (!empty($_GET['keyword'])) {
    ?>
    <h2><?=$_GET['keyword']?></h2>
    <?php

    // 編集中の文書の情報
    $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    $stmt->bindParam(':key', $_GET['keyword'], PDO::PARAM_STR);
    $stmt->execute();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <div>
            <?=$result['sentence']?>
        </div>
    <?php
    }
    
}
?>