<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

if (!empty($_POST['key'])) {

    // 編集中の文書の情報
    $stmt = $pdo->prepare('UPDATE script SET color=:color WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->bindParam(':color', $_POST['color'], PDO::PARAM_STR);
    $stmt->execute();


    // $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    // $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    // $stmt->execute();
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($_POST);
} else {
    echo null;
}
?>