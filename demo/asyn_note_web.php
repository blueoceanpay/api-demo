<?php
error_reporting(E_ALL^E_NOTICE);
/**
 * Created by PhpStorm.
 * User: YUN
 * Date: 2018/5/31
 * Time: 11:34
 */



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QRCode Pay Demo</title>
    <script src="resource/jquery-3.3.1.min.js"></script>
    <script src="resource/bootstrap.min.js"></script>
    <script src="resource/layer/layer.js"></script>
    <script src="resource/formatJson.js"></script>
    <link rel="stylesheet" type="text/css" href="resource/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>

<div class="container">

    <div class="text-center">
        <strong>BlueOceanPay Demo</strong>
    </div>

    <div>
        <h4>回调的数据：</h4>
        <ol>
            <li>显示的是最新一条回调数据</li>
            <li>用户支付成功之后才有回调</li>
        </ol>
    </div>

    <pre id="json_data">

    </pre>

</div>

<script>

    var data = '<?php echo file_get_contents('qrcode/asyn_data.txt');?>';
    $('#json_data').html(formatJson(data))
</script>

</body>
</html>
