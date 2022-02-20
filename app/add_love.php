<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);


if (!empty($_POST['key'])) {
    $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $love = $result['love']+1;

    // ランダムで単語を取得
    $stmt = $pdo->prepare('UPDATE script SET love = :love WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->bindParam(':love', $love, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(array('love' => $love));
} else {
    echo null;
}
