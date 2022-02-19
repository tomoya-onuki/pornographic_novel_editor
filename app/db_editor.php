<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

$finID = 0;

// PDOでDBからデータを取得
function getDB($pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM dict');
    $stmt->execute();

    return $stmt;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="./main.js"></script> -->
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>データベース編集</title>
</head>

<body>



    <div>用語：<textarea id="word" cols="50" rows="2"></textarea></div>
    <div>意味：<textarea id="meaning" cols="50" rows="2"></textarea></div>
    <div>用法：<textarea id="ex_sentence" cols="50" rows="2"></textarea></div>
    <div>作者：<textarea id="author" cols="50" rows="2"></textarea></div>
    <button id="db_add_btn">追加</button>

    <div>削除を押すと、完全に消えます。undoとかないので、気をつけてください。</div>

    <table id="db_tbl" border="1">
        <tr>
            <th>no.</th>
            <th>用語</th>
            <th>意味</th>
            <th>用法</th>
            <th>作者</th>
            <th>削除</th>
        </tr>
        <?php
        $stmt = getDB($pdo);
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr id="word' . $result['id'] . '">';
            echo '<td class="id">' . $result['id'] . '</td>';
            echo '<td class="word">' . $result['word'] . '</td>';
            echo '<td class="meaning">' . $result['meaning'] . '</td>';
            echo '<td class="ex_sentence">' . $result['ex_sentence'] . '</td>';
            echo '<td class="author">' . $result['author'] . '</td>';
            echo '<td><a href="./db_delete.php?id=' . $result['id'] . '">削除</a></td>';
            echo '</tr>';
        }
        $finID = $result['id'];
        ?>
    </table>
</body>


<script>
    $('#db_add_btn').on('click', function() {
        $.post('db_add.php', {
                'word': $('#word').val(),
                'meaning': $('#meaning').val(),
                'ex_sentence': $('#ex_sentence').val(),
                'author': $('#author').val()
            },
            function(data) {
                // console.log(data)
                // location.href = "./db_editor.php";
                if (data != null) {
                    let stmt = '<tr>';
                    for (let key in data) {
                        console.log(key, data[key]);
                        stmt += '<td>' + data[key] + '</td>';
                        // $('#db_tbl').append('<td>' + data[key] + '</td>');
                    }
                    stmt += '<td><a href="./db_delete.php?id='+data.id+'">削除</a></td><tr>';
                    // $('#db_tbl').append('<td><a href="./db_delete.php?id='+data.id+'">削除</a></td>');
                }
            },
            "json"
        )
    });
</script>

</html>