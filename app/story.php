<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>ふたりで書く官能小説</title>

    <script>
        $(function() {
            $('#add_love').click(function() {
                $.post('./add_love.php', {
                        'key': '<?= $_GET['key'] ?>',
                    },
                    function(data) {
                        console.log(data.love);
                        $('#add_love').text('いいね:'+data.love);
                    },
                    "json"
                );
            });
        });
    </script>
</head>

<body class="main1">
    <a href="./" style="color:#fff">TOP</a>
    <div class="edit">
        <?php
        if (!empty($_GET['key'])) {
            // 編集中の文書の情報
            $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
            $stmt->bindParam(':key', $_GET['key'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('SELECT * FROM dict WHERE word = :word');
            $stmt->bindParam(':word', $result['word'], PDO::PARAM_STR);
            $stmt->execute();
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>
        <div class="edit_meaning"><?= $result2['meaning'] ?></div>
        <div class="edit_word"><?= $result['word'] ?></div>
        <div class="edit_script"><?= $result['sentence'] ?></div>
        
        <svg class="draft_ellipse" style="transform:rotate(90deg)" width="30" height="44" viewBox="0 0 30 44" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M30 22C30 37.4791 25.5539 44 15 44C4.44607 44 0 37.4791 0 22C0 6.5209 4.44607 0 15 0C25.5539 0 30 6.5209 30 22Z"
                fill="#FCF9FB" />
        </svg>
        <button id="add_love">いいね:<?= $result['love'] ?></button>
    </div>
</body>

</html>