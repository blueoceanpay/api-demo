<?php
session_start();
error_reporting(E_ALL^E_NOTICE);

// 获取SESSION
$user_params = $_SESSION['user_params'];
$api_host = $_SESSION['api_host'];
if (empty($user_params) || empty($api_host)) {
    header("location:login.php");
}
require_once "../utils/Language.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BlueOceanPay Refund Demo</title>
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
        <strong>BlueOceanPay Refund Demo</strong>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="form" action="refund_api.php">
                <div class="form-group">
                    <label for="sn">order_sn:</label>
                    <input type="text" name="sn" class="form-control" id="sn" placeholder="与out_trade_no二选一,优先使用sn">
                </div>
                <div class="form-group">
                    <label for="out_trade_no">out_trade_no:</label>
                    <input type="text" name="out_trade_no" class="form-control" id="out_trade_no" placeholder="商户订单号">
                </div>
                <div class="form-group">
                    <label for="refund_fee">refund_fee:</label>
                    <input type="text" name="refund_fee" class="form-control" id="refund_fee" placeholder="可选参数，默认为订单总额，即全额退款 支付宝不支持部分退款">
                </div>
                <div class="form-group">
                    <label for="refund_desc">refund_desc:</label>
                    <input type="text" name="refund_desc" class="form-control" id="refund_desc" placeholder="退款说明">
                </div>
                <div class="form-group">
                    <label for="password">password:</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="退款密码">
                </div>
                <div class="form-group">
                    <button type="button" class="form-control btn btn-success" id="submit"><?php echo Language::lang('submit');?></button>
                </div>
            </form>
        </div>

        <div class="col-md-6">
            <div>
                <strong><?php echo Language::lang("signature_string"); ?></strong>
                <p id="sign_string"></p>
            </div>
            <div>
                <strong><?php echo Language::lang('request_params');?></strong>
                <pre id="request_params"></pre>
            </div>
            <div>
                <strong><?php echo Language::lang('response_params');?></strong>
                <pre id="response_params"></pre>
            </div>
        </div>

    </div>

</div>

<script>

    $('#submit').click(function () {
        var url = $('#form').attr('action');

        var password = $('#password').val();
        var sn = $('#sn').val();
        var out_trade_no = $('#out_trade_no').val();

        if (sn == "" && out_trade_no == ""){
            layer.msg('order_sn与out_trade_no必须二选一');
            return;
        }
        if (password == ""){
            layer.msg('请填写密码');
            return;
        }

        // 加载动画
        var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

        $.post(url,$('#form').serialize(),function (result) {
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







