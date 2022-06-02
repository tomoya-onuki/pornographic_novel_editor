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



$img_array = array();
foreach (glob('./img/{*.jpg}', GLOB_BRACE) as $file) {
    if (is_file($file)) {
        array_push($img_array, $file);
    }
}
// var_dump($img_array);
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
        // $(function() {
        //     $('#create').on('click', function() {
        //         $("#modal").fadeIn();
        //     });
        //     $('#esc').on('click', function() {
        //         $("#modal").fadeOut();
        //     });
        // });
    </script>
</head>

<body class="main0">
    <svg class="frame0" width="1235" height="962" viewBox="0 0 1235 962" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.5 699.5C0.5 945.539 46.2531 1120.26 146.198 1233.48C246.131 1346.68 400.336 1398.5 617.5 1398.5C834.664 1398.5 988.869 1346.68 1088.8 1233.48C1188.75 1120.26 1234.5 945.539 1234.5 699.5C1234.5 453.461 1188.75 278.737 1088.8 165.519C988.869 52.3165 834.664 0.5 617.5 0.5C400.336 0.5 246.131 52.3165 146.198 165.519C46.2531 278.737 0.5 453.461 0.5 699.5Z" stroke="black" />
    </svg>
    <svg class="frame1" width="1258" height="975" viewBox="0 0 1258 975" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.500122 712.5C0.500122 963.113 47.1052 1141.09 148.914 1256.41C250.71 1371.72 407.79 1424.5 629 1424.5C850.21 1424.5 1007.29 1371.72 1109.09 1256.41C1210.89 1141.09 1257.5 963.113 1257.5 712.5C1257.5 461.887 1210.89 283.913 1109.09 168.589C1007.29 53.2799 850.21 0.5 629 0.5C407.79 0.5 250.71 53.2799 148.914 168.589C47.1052 283.913 0.500122 461.887 0.500122 712.5Z" stroke="black" />
    </svg>

    <div class="ui">
        <div class="my_title">＃ふたりで書く官能小説</div>
        <!-- <div class="my_bookbinding_ellipse ellipse"></div> -->
        <svg class="my_bookbinding_ellipse" width="30" height="67" viewBox="0 0 30 67" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M30 33.5C30 57.0705 25.5539 67 15 67C4.44607 67 0 57.0705 0 33.5C0 9.92955 4.44607 0 15 0C25.5539 0 30 9.92955 30 33.5Z" fill="#3F3643" />
        </svg>
        <button class="my_bookbinding">製本する</button>
        <!-- <div class="my_create_ellipse ellipse"></div> -->
        <svg class="my_create_ellipse" width="30" height="67" viewBox="0 0 30 67" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M30 33.5C30 57.0705 25.5539 67 15 67C4.44607 67 0 57.0705 0 33.5C0 9.92955 4.44607 0 15 0C25.5539 0 30 9.92955 30 33.5Z" fill="#3F3643" />
        </svg>
        <button class="my_create" id="create">作成する</button>
        <script>
            $("#create").on('click', function() {
                location.href = './create.php?key=<?= $key ?>';
            });
        </script>
    </div>

    <!-- <div>作品一覧</div> -->
    <div class="flex">
        <?php
        $stmt = $pdo->prepare('SELECT * FROM script');
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idx = random_int(0, count($img_array) - 1);
            if ($result['done']) {
        ?>
                <div class="flex_item">
                    <a href="./story.php?key=<?= $result['key'] ?>">
                        <div class="story_mask" style="background:<?= $result['color'] ?>"></div>
                        <img class="story_img" style="background:<?= $result['color'] ?>" src="<?= $img_array[$idx] ?>" alt="">
                        <div class="story_title"><?= $result['word'] ?></div>
                    </a>
                </div>

        <?php
            }
        }
        ?>
    </div>



    <!-- <div id="modal" class="my_create_modal">
        <div class="title">合言葉で小説をはじめる。</div>
        <div class="esc" id="esc">×</div>
        <form action="create.php" method="post">
            <input id="copyTarget" class="form0" type="text" value="<?= $key ?>" readonly name="key">
            <input type="hidden" value="1" name="editor">
            <svg class="btn0_ellipse" width="33" height="15" viewBox="0 0 33 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.5 -7.21238e-07C28.1093 -1.2287e-06 33 2.22303 33 7.5C33 12.777 28.1093 15 16.5 15C4.89067 15 -9.71718e-08 12.777 -3.27835e-07 7.5C-5.58499e-07 2.22303 4.89067 -2.13778e-07 16.5 -7.21238e-07Z" fill="#FCF9FB" />
            </svg>
            <input type="submit" value="つくる" class="btn0">
        </form>
        <form action="top.php" method="post">
            <input class="form1" type="text" value="" name="key">
            <input type="hidden" value="-1" name="editor">
            <svg class="btn1_ellipse" width="44" height="15" viewBox="0 0 44 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 -9.61651e-07C37.4791 -1.63826e-06 44 2.22303 44 7.5C44 12.777 37.4791 15 22 15C6.5209 15 -9.71718e-08 12.777 -3.27835e-07 7.5C-5.58499e-07 2.22303 6.5209 -2.85037e-07 22 -9.61651e-07Z" fill="#FCF9FB" />
            </svg>
            <input type="submit" value="あわせる" class="btn1 ellipse">
        </form>
    </div> -->




    <script>
        $(function() {
            $('#copyTarget').on('click', function() {

            });
        });

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