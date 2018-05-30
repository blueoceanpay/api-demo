<?php
session_start();

// 获取SESSION
$user_params = $_SESSION['user_params'];
$api_host = $_SESSION['api_host'];
if (empty($user_params) || empty($api_host)) {
    header("location:login.html");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deal Order</title>
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
        <strong>BlueOceanPay Deal Order Demo</strong>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="form" action="deal_order_api.php">
                <div class="form-group">
                    <label for="sn">Order Sn:</label>
                    <input type="text" name="sn" class="form-control" id="sn" placeholder="与out_trade_no二选一,优先使用sn">
                </div>
                <div class="form-group">
                    <label for="out_trade_no">out_trade_no:</label>
                    <input type="text" name="out_trade_no" class="form-control" id="out_trade_no" placeholder="商户订单号">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-success submit_button" id="check">Check</button>
                    <button type="button" class="btn btn-warning submit_button" id="close">Close</button>
                    <button type="button" class="btn btn-danger submit_button" id="reverse">Reverse</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div>
                <strong>请求的参数</strong>
                <pre id="request_params"></pre>
            </div>
            <div>
                <strong>签名字符串</strong>
                <span id="sign_string"></span>
            </div>
            <div>
                <strong>响应的参数</strong>
                <pre id="response_params"></pre>
            </div>
        </div>
    </div>

</div>

<script>

    $('.submit_button').click(function () {
        var url = $('#form').attr('action');
        var sn = $('#sn').val();
        var out_trade_no = $('#out_trade_no').val();
        var submit_type = $('.submit_button').attr('id');

        if (sn == "" && out_trade_no == ""){
            layer.msg('order_sn 与 out_trade_no二选一');
            return;
        }

        // 加载动画
        var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

        $.post(url,{"sn":sn,"out_trade_no":out_trade_no,"submit_type":submit_type},function (result) {
            layer.close(index);
            var result = JSON.parse(result);
            layer.msg(result.message);

            $('#response_params').empty();
            $('#request_params').empty();

            $('#sign_string').html(result['data']['sign_string']);
            if (result.code == 200){
                $('#response_params').html(formatJson(result['data']['response_params']))
                $('#request_params').html(formatJson(result['data']['request_params']))

            }
        });

    });

</script>

</body>
</html>









