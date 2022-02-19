<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

var_dump($_POST);
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
        最初のワード : <?= $word ?>
    </div>

    <div id="status"></div>
    <div id="script"><?= $sentence ?></div>

    <textarea name="sentence" id="sentence" cols="30" rows="10"></textarea>
    <button id="update">更新</button>




    <script>
        const myEditId = <?= $_POST['editor'] ?>;
        const editor = <?= $editor ?>;
        check_editor(editor);

        console.log(editor);
        console.log($('#sentence').val());

        $('#update').click(function() {
            let new_sentence = $('#script').html() + '<br>' + $('#sentence').val();
            $.post('update.php', {
                    'sentence': new_sentence,
                    'editor': editor * -1,
                    'key': '<?= $_POST['key'] ?>'
                },
                function(data) {
                    update(data)
                },
                "json"
            )
        });
        window.setInterval(check_db(), 1000);

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
            $('#sentence').val('');
            check_editor(data.editor);
        }

        function check_db() {
            $.post('update.php', {
                    'key': '<?= $_POST['key'] ?>'
                },
                function(data) {
                    update(data);
                },
                "json"
            )
        }
    </script>
</body>

</html>