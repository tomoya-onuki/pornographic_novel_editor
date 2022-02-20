<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);


if (!empty($_POST['key'])) {
    // ランダムで単語を取得
    $stmt = $pdo->prepare('SELECT * FROM dict ORDER BY random() LIMIT 1');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $word = $result['word'];
    
    // $stmt = $pdo->prepare('UPDATE script SET word = :word WHERE key = :key');
    // $stmt->bindParam(':word', $word, PDO::PARAM_STR);
    // $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    // $stmt->execute();
    
    $sentence = '';
    $line = 0;
    $love = 0;
    $stmt = $pdo->prepare('INSERT INTO script (key, sentence, editor, word, line, love) VALUES (:key, :sentence, :editor, :word, :line, :love)');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->bindParam(':sentence', $sentence, PDO::PARAM_STR);
    $stmt->bindParam(':editor', $_POST['editor'], PDO::PARAM_INT);
    $stmt->bindParam(':word', $word, PDO::PARAM_STR);
    $stmt->bindParam(':line', $line, PDO::PARAM_INT);
    $stmt->bindParam(':love', $love, PDO::PARAM_INT);
    $stmt->execute();


    header("Location: ./top.php", true, 307);
}


?>