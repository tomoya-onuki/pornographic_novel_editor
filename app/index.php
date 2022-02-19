<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);


function random_keyword()
{
    $length = 8;
    return base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
}
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
    <a href="./db_editor.php">データベース編集</a>


    <?php

    $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    $stmt->bindParam(':key', $_POST['keyword'], PDO::PARAM_STR);
    $stmt->execute();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <div>
            <?= $result['word'] ?>
        </div>

    <?php
    }



    $keyword = random_keyword();
    $sentence = '';
    $editor = 1;
    $stmt = $pdo->prepare('INSERT INTO script (key, sentence, editor, word) VALUES (:key, :sentence, :editor, :word)');
    $stmt->bindParam(':key', $keyword, PDO::PARAM_STR);
    $stmt->bindParam(':sentence', $sentence, PDO::PARAM_STR);
    $stmt->bindParam(':editor', $editor, PDO::PARAM_INT);
    $stmt->bindParam(':word', 'word', PDO::PARAM_INT);
    $stmt->execute();
    ?>



    <div>
        <h2>ゲームを作る人</h2>
        <div>合言葉を共有した人と官能小説を作れます。</div>
        <div>
            <form action="top.php" method="post">
                合言葉 : <input id="copyTarget" type="text" value="<?= $keyword ?>" readonly name="keyword">
                <input type="hidden" value="1" name="editor">
                <input type="submit" value="START">
            </form>
        </div>
        <button onclick="copyToClipboard()">Copy</button>
    </div>

    <div>
        <h2>ゲームに参加する人</h2>
        <div>共有した合言葉で官能小説を作れます。</div>
        <div>
            <form action="top.php" method="post">
                合言葉 : <input type="text" value="" name="keyword">
                <input type="hidden" value="-1" name="editor">
                <input type="submit" value="START">
            </form>
        </div>
    </div>




    <script>
        function copyToClipboard() {
            // コピー対象をJavaScript上で変数として定義する
            var copyTarget = document.getElementById("copyTarget");

            // コピー対象のテキストを選択する
            copyTarget.select();

            // 選択しているテキストをクリップボードにコピーする
            document.execCommand("Copy");

            // コピーをお知らせする
            alert("copied! : " + copyTarget.value);
        }
    </script>
</body>

</html>