<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

var_dump($_POST);
$sentence = "";
$isEditor = false;
$word = "";

if( !empty($_POST['keyword']) && !empty($_POST['editor']) ) {
    
    // 編集中の文書の情報
    $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    $stmt->bindParam(':key', $_POST['keyword'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $sentence = $result['sentence'];
    if (strcmp($result['editor'], $_POST['editor']) == 0 ) {
        $isEditor = true;
    } else {
        $isEditor = false;
    }
    var_dump($result);

    // ランダムで単語を取得
    $stmt = $pdo->prepare('SELECT * FROM dict ORDER BY random() LIMIT 1');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $word = $result['word'];

    var_dump($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>ふたりの官能小説(仮)</title>
</head>
<body>
<h1>ふたりの官能小説(仮)</h1>
    <div>
        最初のワード : <?=$word?>
    </div>

    <div><?=$sentence?></div>
    
    
    <?php if ($isEditor) {?>
        <textarea name="sentence" id="sentence" cols="30" rows="10"></textarea>
    <?php } else {?>
        <textarea name="sentence" id="sentence" cols="30" rows="10" readonly></textarea>
    <?php }?>
</body>
</html>