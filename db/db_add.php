<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

// var_dump($_POST);

if ($_POST['word'] != '' && $_POST['meaning'] != '') {   
    // PDOでDBからデータを取得
    $stmt = $pdo->prepare('INSERT INTO dict (word, meaning, ex_sentence, author) VALUES (:word, :meaning, :ex_sentence, :author)');
    $stmt->bindParam(':word', $_POST['word'], PDO::PARAM_STR);
    $stmt->bindParam(':meaning', $_POST['meaning'], PDO::PARAM_STR);
    $stmt->bindParam(':ex_sentence', $_POST['ex_sentence'], PDO::PARAM_STR);
    $stmt->bindParam(':author', $_POST['author'], PDO::PARAM_STR);
    $stmt->execute();


    $stmt = $pdo->prepare('SELECT * FROM dict WHERE word = :word');
    $stmt->bindParam(':word', $_POST['word'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);
} else {
    echo null;
}

// exit();

?>
