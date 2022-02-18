<?php

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>gyro</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.3.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <link rel="shortcut icon" href="./imgs/icon.png" type="image/png" />
    <link rel="icon" href="./imgs/icon.png" type="image/png" />
    <link rel="apple-touch-icon" href="./imgs/icon.png" />

    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="MOS" />

    <style type="text/css">
        html,
        body {
            text-align: center;
            background-color: #000000;
            font-size: 20px;
            color: #fff;
        }

        body {
            background-color: #000000;
        }

        #mycanvas {
            border: 1px solid #333;
            background-color: #ffffff;
        }
    </style>
</head>

<body>

    <div class="container">
        <canvas id="mychart1" style="position:relative; width:90%; height: 30%;"></canvas>
        <canvas id="mychart2" style="position:relative; width:90%; height: 30%;"></canvas>

        <div id="cdiv">
            <canvas width="80%" height="10%" id="mycanvas"></canvas>
        </div>
        <div style="font-size: 50%;">白のエリアをタッチしながらスマホを振って、指を離すとセンサーで取得したデータがぐらふになります</div>
    </div>

    <script src="main.js"></script>
</body>

</html>