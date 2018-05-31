<?php
session_start();

// 获取SESSION
$user_params = $_SESSION['user_params'];
$api_host = $_SESSION['api_host'];
if (empty($user_params) || empty($api_host)) {
    header("location:login.html");
}
require_once "../utils/CheckMobile.php";
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
        <strong>BlueOceanPay QRCode Pay Demo</strong>
    </div>

    <div class="row">

        <div class="col-md-6">

            <form id="form" action="qrcode_pay_api.php">
                <div class="form-group">
                    <label for="payment">Payment:</label>
                    <select name="payment" class="form-control" id="payment">
                        <option value="micropay">刷卡支付</option>
                        <option value="alipay.qrcode">支付宝二维码</option>
                        <option value="wechat.qrcode">微信二维码</option>
                        <option value="blueocean.qrcode">混合二维码</option>
                    </select>
                </div>
                <div class="form-group form_code">
                    <label for="code">code:</label>
                    <input type="text" class="form-control" name="code" id="code" placeholder="payment为 micropay 时填写">
                </div>
                <div class="form-group">
                    <label for="total_fee">total_fee:</label>
                    <input type="text" class="form-control" name="total_fee" id="total_fee" placeholder="支付金额 单位为 分 如10即0.10元">
                </div>
                <div class="form-group">
                    <label for="discount">discount:</label>
                    <input type="text" class="form-control" name="discount" id="discount" placeholder="优惠金额 单位 分 如25即0.25元,默认为0">
                </div>

                <div class="form-group">
                    <label for="out_trade_no">out_trade_no:</label>
                    <input type="text" class="form-control" name="out_trade_no" id="out_trade_no" placeholder="如果商户有自己的订单系统，可以自己生成订单号">
                </div>
                <div class="form-group">
                    <label for="notify_url">notify_url:</label>
                    <input type="text" class="form-control" name="notify_url" id="notify_url" placeholder="异步通知url" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/demo/get_asyn_note.php' ?>">
                </div>

                <div class="form-group">
                    <button type="button" class="form-control btn btn-success" id="submit">Submit</button>
                </div>

                <div>
                    <span>查看回调数据：</span>
                    <ol>
                        <li>下面的地址notify_url为默认值时有效，仅供参考</li>
                        <li>用户支付成功才有回调</li>
                    </ol>
                    <a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/demo/asyn_note_web.php' ?>" target="_blank"><?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/demo/asyn_note_web.php' ?></a>
                </div>

            </form>

        </div>

        <div class="col-md-6">
            <div id="qrcode">
                <strong>QRCode</strong>
                <img src="" id="qrcode_image">
                <?php if (CheckMobile::is_mobile()){ ?>
                <a href="" class="go_to_pay">跳转支付</a>
                <?php } ?>
            </div>

            <div>
                <strong>签名参数</strong>
                <span id="sign_string"></span>
            </div>

            <div>
                <strong>请求的参数</strong>
                <pre id="request_params"></pre>
            </div>
            <div>
                <strong>响应的参数</strong>
                <pre id="response_params"></pre>
            </div>
        </div>
    </div>

</div>

<script>

    // 全局的
    out_trade_no = "";

    $(function () {
        var payment = $('#payment').val();
        if (payment == 'micropay') {
            $('.form_code').css('display', 'block');
        } else {
            $('.form_code').css('display', 'none');
        }
        $('.go_to_pay').hide();
    })

    $('#payment').change(function () {
        var payment = $('#payment').val();
        if (payment == 'micropay') {
            $('.form_code').css('display', 'block');
        } else {
            $('.form_code').css('display', 'none');
        }
    });

    $('#submit').click(function () {

        var payment = $('#payment').val();
        var total_fee = $('#total_fee').val();
        var discount = $('#discount').val();// 选题
        var code = $('#code').val();// 选填
        var out_trade_no = $('#out_trade_no').val();// 选填
        var notify_url = $('#notify_url').val();// 选填

        if (payment == "") {
            layer.msg('Payment不能为空');
            return;
        }
        if (payment == "micropay") {
            if (code == "") {
                layer.msg('Code不能为空');
                return;
            }
        }

        if (total_fee == "" || total_fee < 2) {
            layer.msg("total_fee不能为空且不能小于2")
            return;
        }

        var url = $('#form').attr('action');

        // 加载动画
        var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

        $('.go_to_pay').hide();

        $.post(url, $('#form').serialize(), function (data) {
            layer.close(index);
            var result = JSON.parse(data);
            layer.msg(result.message);

            $('#response_params').empty();
            $('#request_params').empty();

            $('#sign_string').html(result['data']['signString']);
            if (result.code == 200) {
                $('#qrcode').show();
                $('#qrcode_image').attr('src', result.data.qrcode_url);

                if (payment == "alipay.qrcode" || payment == "blueocean.qrcode"){
                    $('.go_to_pay').show();
                    $('.go_to_pay').attr("href",result['data']['response_params']['qrcode']);
                }

                window.out_trade_no = result.data.response_params.out_trade_no;

                $('#response_params').html(formatJson(result['data']['response_params']))
                $('#request_params').html(formatJson(result['data']['request_params']))


                // 调用刷新
                refresh_data();

            }
        });

    });

    // 刷新数据
    function refresh_data() {

        if (out_trade_no != ""){
            setTimeout(function () {

                // 加载动画
                //var index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
                var index2 = layer.msg('刷新订单状态', {
                    icon: 16
                    ,shade: 0.01
                });

                $.post("./deal_order_api.php",{"out_trade_no":out_trade_no,"submit_type":"check"},function (result) {
                    layer.close(index2);

                    var result = JSON.parse(result);

                    //$('#sign_string').html(result['data']['signString']);
                    if (result.code == 200){
                        $('#response_params').html(formatJson(result['data']['response_params']))
                        //$('#request_params').html(formatJson(result['data']['request_params']))
                    }

                })

                refresh_data()
            },8000)
        }
    }


</script>

</body>
</html>








