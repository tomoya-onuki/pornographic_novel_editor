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
$line = 0;
$ex_sentence = "";
$participant = 0;

// var_dump($_POST);

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
        $line = $result['line'];
        $participant = $result['participant'] + 1;

        if ($participant > 2) {
            header("Location: ./error.php?stmt=この部屋には入れません");
        }
    } else {
        header("Location: ./error.php?stmt=部屋が見つかりません");
    }


    $stmt = $pdo->prepare('UPDATE script SET participant = :participant WHERE key = :key');
    $stmt->bindParam(':participant', $participant, PDO::PARAM_INT);
    $stmt->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
    $stmt->execute();


    $stmt = $pdo->prepare('SELECT * FROM dict WHERE word = :word');
    $stmt->bindParam(':word', $word, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $meaning = $result['meaning'];
    $ex_sentence = $result['ex_sentence'];
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
            const myEditId = <?= $_POST['editor'] ?>;
            const editor = <?= $editor ?>;
            let line = <?= $line ?>;
            check_editor(editor);

            // console.log(editor);
            // console.log($('#sentence').val());


            function check_editor(_editor) {
                if (_editor === myEditId) {
                    $('#status').text('あなたのばん');
                    $('#sentence').attr('readonly', false);
                    $('#update').fadeIn();
                    $('.update_ellipse').fadeIn();
                } else {
                    $('#status').text('あいてのばん');
                    $('#sentence').attr('readonly', true);
                    $('#update').fadeOut();
                    $('.update_ellipse').fadeOut();
                }
            }

            function update(data) {
                console.log(data.editor);
                $('#script').html(data.sentence);
                check_editor(data.editor);
            }

            $('#update').click(function() {
                const sentence = $('#sentence').val();
                console.log(sentence);
                if (sentence) {
                    let new_sentence = $('#script').html() + '<div class="sentence">' + sentence + '</div>';
                    $.post('update.php', {
                            'sentence': new_sentence,
                            'editor': myEditId * -1,
                            'key': '<?= $_POST['key'] ?>',
                            'line': line + 1
                        },
                        function(data) {
                            // update(data);
                            $('#script').html(data.sentence);
                            check_editor(data.editor);
                            line = data.line;
                            if (data.line > 5) {
                                $('#status').text('終了');
                                $('#sentence').attr('readonly', true);
                            }
                            $('#sentence').val('');
                        },
                        "json"
                    );
                }
            });

            // DBの定期的な監視
            window.setInterval(function() {
                console.log('check data base')
                $.post('check.php', {
                        'key': '<?= $_POST['key'] ?>'
                    },
                    function(data) {
                        // update(data);
                        console.log(data.line);
                        line = data.line;
                        $('#script').html(data.sentence);
                        check_editor(data.editor);
                        if (data.line > 5) {
                            $('#status').text('終了');
                            $('#sentence').attr('readonly', true);
                        }
                    },
                    "json"
                )
            }, 1000);


            $('#help').on('click', function() {
                $('#ex_sentence').fadeIn();
                $('#script').fadeOut();
            });
            $('#help_close').on('click', function() {
                $('#ex_sentence').fadeOut();
                $('#script').fadeIn();
            });


            $('#picker').on('input', function () {
                let hex = $(this).val();
                $('#code').text(hex);
                $('#code').css('color', hex);
                $('#color_select').css('background', hex);
            });
            $('#color_submit').on('click', function () {
                $.post('update_color.php', {
                    'key': '<?= $_POST['key'] ?>',
                    'color': $('#picker').val()
                },
                    function (data) {
                        $('#editor').fadeIn();
                        $('#color_select').fadeOut();
                    },
                    "json"
                )

            });
        });
    </script>
</head>

<body class="main1">
    <!-- <a href="./"><h1>ふたりでかく官能小説</h1></a> -->
    <div id="color_select">
    <div class="edit_msg">＊ふたりだけの色を指定してください。</div>
        <svg class=".c_ellipse" width="300" height="440" viewBox="0 0 30 44" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M30 22C30 37.4791 25.5539 44 15 44C4.44607 44 0 37.4791 0 22C0 6.5209 4.44607 0 15 0C25.5539 0 30 6.5209 30 22Z"
                fill="#FCF9FB" />
        </svg>
        <div id="code">#000000</div>
        <input id="picker" type="color" name="col" value="#000000">
        <button id="color_submit">決定</button>
    </div>


    <div id="editor" class="edit">
        <div class="edit_msg">＊必ずこの「ことば」を使って小説を書いてください。描き終わったら、左下の入稿ボタンをタッチしてください。</div>
        <div class="edit_key">合言葉:<?= $_POST['key'] ?></div>
        <div class="edit_word"><?= $word ?></div>
        <div class="edit_meaning"><?= $meaning ?></div>

        <div id="status"></div>
        <div id="script" class="edit_script"><?= $sentence ?></div>
        <div id="ex_sentence" class="edit_script">
            <div>
                <span id="help_close">×</span>
                例文
            </div>
            <?= $ex_sentence ?>
        </div>
        <button id="help">help</button>

        <textarea name="sentence" id="sentence" cols="30" rows="10" maxlength="30"></textarea>
        <svg class="update_ellipse" width="30" height="44" viewBox="0 0 30 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M30 22C30 37.4791 25.5539 44 15 44C4.44607 44 0 37.4791 0 22C0 6.5209 4.44607 0 15 0C25.5539 0 30 6.5209 30 22Z"
                fill="#FCF9FB" />
        </svg>
        <svg class="draft_ellipse" width="30" height="44" viewBox="0 0 30 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M30 22C30 37.4791 25.5539 44 15 44C4.44607 44 0 37.4791 0 22C0 6.5209 4.44607 0 15 0C25.5539 0 30 6.5209 30 22Z"
                fill="#FCF9FB" />
        </svg>
        <button id="update">更新</button>
        <button id="draft" onclick="location.href='./story.php?key=<?=$_POST['key']?>'">入稿</button>

        <!-- <form action="./story.php" method="get">
            <input id="draft" name="draft" value="入稿">
            <input type="hidden" name="key" value="">
        </form> -->
    </div>




    <script>

    </script>
</body>

</html>