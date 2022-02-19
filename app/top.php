<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

// var_dump($_POST);
$sentence = "";
$word = "";
$editor = 0;


if (!empty($_POST['key'])) {

    // 編集中の文書の情報
    $stmt = $pdo->prepare('SELECT * FROM script WHERE key = :key');
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $sentence = $result['sentence'];
        $word = $result['word'];
        $editor = $result['editor'];
    } else {
        header("Location: ./error.html");
    }
    // var_dump($result);
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
    <script>
        $(function() {
            // DBの定期的な監視
            window.setInterval(function() {
                console.log('check data base')
                $.post('check.php', {
                        'key': '<?= $_POST['key'] ?>'
                    },
                    function(data) {
                        update(data);
                    },
                    "json"
                )
            }, 1000);
        });
    </script>
</head>

<body class="main1">
    <div class="edit_msg">＊描き終わったら、左下の入稿ボタンをタッチしてください。</div>
    <!-- <a href="./"><h1>ふたりの官能小説(仮)</h1></a> -->
    <div class="edit">
        <div class="edit_key"><?= $_POST['key'] ?></div>
        <div class="edit_word"><?= $word ?></div>
        <div class="edit_meaning"><?= $word ?></div>
        
        <div id="status"></div>
        <div id="script"><?= $sentence ?></div>
    </div>

    <div class="edit_form">
        <textarea name="sentence" id="sentence" cols="30" rows="10"></textarea>
        <button id="update">更新</button>
        <button id="draft">入稿</button>
    </div>




    <script>
        const myEditId = <?= $_POST['editor'] ?>;
        const editor = <?= $editor ?>;
        check_editor(editor);

        // console.log(editor);
        // console.log($('#sentence').val());

        $('#update').click(function() {
            let new_sentence = $('#script').html() + '<div class="sentence">' + $('#sentence').val() + '</div>';
            $.post('update.php', {
                    'sentence': new_sentence,
                    'editor': myEditId * -1,
                    'key': '<?= $_POST['key'] ?>'
                },
                function(data) {
                    update(data);
                    $('#sentence').val('');
                },
                "json"
            )
        });

        function check_editor(_editor) {
            if (_editor === myEditId) {
                $('#status').text('あなたのばん');
                $('#sentence').attr('readonly', false);
            } else {
                $('#status').text('あいてのばん');
                $('#sentence').attr('readonly', true);
            }
        }

        function update(data) {
            console.log(data.editor);
            $('#script').html(data.sentence);
            check_editor(data.editor);
        }
    </script>
</body>

</html>