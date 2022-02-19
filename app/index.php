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

$key = random_keyword();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ふたりて書く官能小説</title>

    <script>
        $(function() {
            $('#create').on('click', function() {
                $("#my_create_modal").fadeIn();
            });
            $('#esc').on('click', function() {
                $("#my_create_modal").fadeOut();
            });
        });
    </script>
</head>

<body class="main0">
    <div class="frame0"></div>
    <div class="frame1"></div>

    <div class="my_title">#ふたりて書く官能小説</div>
    <div class="my_bookbinding">製本する</div>
    <div class="my_create" id="create">作成する</div>

    <!-- <div>作品一覧</div> -->
    <?php

    $stmt = $pdo->prepare('SELECT * FROM script');
    $stmt->execute();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <div>
            <a href="./story.php?key=<?= $result['key'] ?>">
                <?= $result['word'] ?>
            </a>
        </div>

    <?php } ?>



    <div class="my_create_modal">
        <div class="esc"></div>
        <form action="create.php" method="post">
            合言葉 : <input id="copyTarget" class="form0" type="text" value="<?= $key ?>" readonly name="key">
            <input type="hidden" value="1" name="editor">
            <input type="submit" value="つくる" class="btn0">
        </form>
        <!-- <button onclick="copyToClipboard()">Copy</button> -->
    </div>

    <div>
        <form action="top.php" method="post">
            合言葉 : <input class="form1" type="text" value="" name="key">
            <input type="hidden" value="-1" name="editor">
            <input type="submit" value="START" class="btn1">
        </form>
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