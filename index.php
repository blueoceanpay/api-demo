<?php
session_start();

$lang = $_GET['lang'];
if (empty($lang)){
    $lang = $_SESSION['language'];
    if (empty($lang)){
        $lang = "cn";
    }
}
$_SESSION['language'] = $lang;

require_once "./utils/Language.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BlueOcean Pay</title>
    <script src="demo/resource/jquery-3.3.1.min.js"></script>
    <script src="demo/resource/bootstrap.min.js"></script>
    <script src="demo/resource/layer/layer.js"></script>
    <link rel="stylesheet" type="text/css" href="demo/resource/bootstrap.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <style type="text/css">
        .tab_text{
            margin-top: 10px;
        }
        .tab_text a{
            width: 100%;
        }
    </style>

</head>
<body>



<div class="container">
    <div class="text-center">
        <strong>BlueOceanPay Demo</strong>
    </div>

    <div class="col-md-2">
        <form id="form" class="" method="get" action="">
            <div class="form-group">
                <label for="">选择语言：</label>
                <select name="lang" id="language" class="form-control">
                    <option value="cn" <?php if ($_SESSION['language'] == "cn"){echo "selected";}?>>简体中文</option>
                    <option value="ct" <?php if ($_SESSION['language'] == "ct"){echo "selected";}?>>繁體中文</option>
                    <option value="en" <?php if ($_SESSION['language'] == "en"){echo "selected";}?>>English</option>
                </select>
            </div>
        </form>
    </div>


    <div class="text-center row">

        <div class="col-md-12 tab_text">
            <a href="demo/login.php" class="btn btn-success" target="_blank"><?php echo Language::lang('login_in');?></a>
        </div>

        <div class="col-md-12 tab_text">
            <a href="demo/qrcode_pay.php" class="btn btn-success" target="_blank"><?php echo Language::lang('qrcode_pay'); ?></a>
        </div>

        <div class="col-md-12 tab_text">
            <a href="demo/refund.php" class="btn btn-success" target="_blank"><?php echo Language::lang('refund'); ?></a>
        </div>

        <div class="col-md-12 tab_text">
            <a href="demo/deal_order.php" class="btn btn-success" target="_blank"><?php echo Language::lang('deal_order'); ?></a>
        </div>

        <div class="col-md-12 tab_text">
            <a href="demo/order_list.php" class="btn btn-success" target="_blank"><?php echo Language::lang('order_list'); ?></a>
        </div>

    </div>

    <div style="margin-top: 100px">
        <span>
            <?php echo Language::lang('code_download_url'); ?>:<a href="https://github.com/blueoceanpay/api-demo" target="_blank">https://github.com/blueoceanpay/api-demo</a>
        </span>
    </div>

</div>

<script>
    $('#language').change(function () {
        $('#form').submit();
    });
</script>


</body>
</html>
