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
                $.post('add_love.php', {
                        'key': '<?= $_POST['key'] ?>',
                    },
                    function(data) {
                        // update(data);
                        $(this).val('いいね:'+data.love);
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
        <button id="add_love">いいね:<?= $result['love'] ?></button>
    </div>
</body>

</html>