<?php

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>MOS(Motion & Orientation Sensor)</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
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

    <!-- // OGP tags -->
    <meta property="og:title" content="MOS" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="//dotnsf.github.io/mos/" />
    <meta property="og:image" content="//dotnsf.github.io/mos/imgs/icon.png" />
    <meta property="og:site_name" content="MOS" />
    <meta property="og:description" content="MOS : Mostion & Orientation Sensor" />
    <!-- OGP tags // -->

    <!-- // Twitter Card -->
    <meta property="twitter:card" content="summary" />
    <meta property="twitter:site" content="@dotnsf" />
    <meta property="twitter:creator" content="@dotnsf" />
    <meta property="twitter:url" content="//dotnsf.github.io/mos/" />
    <meta property="twitter:image" content="//dotnsf.github.io/mos/imgs/icon.png" />
    <meta property="twitter:title" content="MOS" />
    <meta property="twitter:description" content="MOS : Mostion & Orientation Sensor" />
    <!-- Twitter Card // -->

    <style type="text/css">
        html,
        body {
            text-align: center;
            background-color: #fafafa;
            font-size: 20px;
            color: #333;
        }

        body {
            background-color: #ffffcc;
        }

        #mycanvas {
            border: 1px solid #333;
            background-color: #ffcccc;
        }
    </style>
</head>

<body>

    <div class="container">
        <canvas id="mychart1" style="position:relative; width:90%; height: 30%;"></canvas>
        <canvas id="mychart2" style="position:relative; width:90%; height: 30%;"></canvas>

        <div id="cdiv">
            <canvas width="80%" height="30%" id="mycanvas"></canvas>
        </div>
    </div>

    <script>
        
    </script>
</body>

</html>