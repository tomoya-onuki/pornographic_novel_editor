<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

if (!empty($_POST['key'])) {

    // 編集中の文書の情報
    $stmt = $pdo->prepare('UPDATE script SET (sentence, editor, line)=(:sentence, :editor, :line) WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->bindParam(':sentence', $_POST['sentence'], PDO::PARAM_STR);
    $stmt->bindParam(':editor', $_POST['editor'], PDO::PARAM_STR);
    $stmt->bindParam(':line', $_POST['line'], PDO::PARAM_INT);
    $stmt->execute();


    $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
} else {
    echo null;
}
?>