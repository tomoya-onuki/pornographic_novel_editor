<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

// var_dump($_POST);
$sentence = "";
$word = "";
$editor = 0;
$meaning = "";


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

    $stmt = $pdo->prepare('SELECT * FROM dict WHERE word = :word');
    $stmt->bindParam(':word', $word, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $meaning = $result['meaning'];
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
    <title>ふたりで書く官能小説</title>
    <script>
        $(function() {
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
        
            // DBの定期的な監視
            window.setInterval(function() {
                console.log('check data base')
                $.post('check.php', {
                        'key': '<?= $_POST['key'] ?>'
                    },
                    function(data) {
                        // update(data);
                        console.log(data.editor);
                        $('#script').html(data.sentence);
                        check_editor(data.editor);
                    },
                    "json"
                )
            }, 1000);
        });
    </script>
</head>

<body class="main1">
    <!-- <a href="./"><h1>ふたりの官能小説(仮)</h1></a> -->
    <div class="edit">
        <div class="edit_msg">＊描き終わったら、左下の入稿ボタンをタッチしてください。</div>
        <div class="edit_key">合言葉:<?= $_POST['key'] ?></div>
        <div class="edit_word"><?= $word ?></div>
        <div class="edit_meaning"><?= $meaning ?></div>

        <div id="status"></div>
        <div id="script"><?= $sentence ?></div>

        <textarea name="sentence" id="sentence" cols="30" rows="10"></textarea>
        <div class="update_ellipse ellipse"></div>
        <div class="draft_ellipse ellipse"></div>
        <button id="update">更新</button>

        <button id="draft" onclick="loction.href='./story.php?key=<?=$_POST['key']?>'">入稿</button>
        <!-- <form action="./story.php" method="get">
            <input id="draft" name="draft" value="入稿">
            <input type="hidden" name="key" value="">
        </form> -->
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
                    // update(data);
                    console.log(data.editor);
                    $('#script').html(data.sentence);
                    check_editor(data.editor);
                    $('#sentence').val('');
                },
                "json"
            )
        });
    </script>
</body>

</html>