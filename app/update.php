<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

if (!empty($_POST['key'])) {

    // 編集中の文書の情報
    $stmt = $pdo->prepare('UPDATE script SET (sentence, editor)=(:sentence, :editor) WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->bindParam(':sentence', $_POST['sentence'], PDO::PARAM_STR);
    $stmt->bindParam(':editor', $_POST['editor'], PDO::PARAM_STR);
    $stmt->execute();
    
    echo json_encode($_POST);
} else {
    echo null;
}
?>