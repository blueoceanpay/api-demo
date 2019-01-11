<?php
require "../utils/Language.php";
error_reporting(E_ALL^E_NOTICE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login In</title>
    <script src="./resource/jquery-3.3.1.min.js"></script>
    <script src="./resource/bootstrap.min.js"></script>
    <script src="./resource/layer/layer.js"></script>
    <link rel="stylesheet" type="text/css" href="./resource/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>

<div class="container">

    <div class="text-center">
        <strong>BlueOceanPay Login Demo</strong>
    </div>


    <form id="form" action="login_api.php">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" id="email" value="">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" id="password" value="">
        </div>
        <div class="form-group">
            <select name="api_host" class="form-control" id="api_host">
                <option value="https://api.hk.blueoceanpay.com" selected>HongKong</option>
                <option value="https://api.au.blueoceanpay.com">Australia</option>
                <option value="https://api.us.blueoceanpay.com">United States</option>
                <option value="https://api.uk.blueoceanpay.com">United Kingdom</option>
            </select>
        </div>
        <div class="form-group">
            <button type="button" class="form-control btn btn-success" id="submit"><?php echo Language::lang('login_in');?></button>
        </div>
    </form>

    <div>
        <h6><?php echo Language::lang('test_account');?>:</h6>

    </div>
</div>






<script>

    $('#submit').click(function () {
        var url = $('#form').attr('action');
        var email = $('#email').val();
        var password = $('#password').val();
        var api_host = $('#api_host').val();

        if (email == ""){
            layer.msg('请填写账号');
            return;
        }
        if (password == ""){
            layer.msg('请填写账号');
            return;
        }
        // 加载动画
        var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

        $.post(url,{"email":email,"password":password,"api_host":api_host},function (result) {
            layer.close(index);
            var result = JSON.parse(result);
            layer.msg(result.message);
            if (result.code == 200){
                window.location.href="../index.php";
            }
        });

    });

</script>

</body>
</html>