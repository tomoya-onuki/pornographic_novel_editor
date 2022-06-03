<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

if (!empty($_POST['key'])) {
    $done = true;
    $participant = 0;
    $stmt = $pdo->prepare('UPDATE script SET (done, participant) = (:done, :participant) WHERE key = :key');
    $stmt->bindParam(':done', $done, PDO::PARAM_BOOL);
    $stmt->bindParam(':participant', $participant, PDO::PARAM_INT);
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode($result);
} else {
    echo null;
}

// header('Location: ./story.php?key='.$_GET['key']);
?>