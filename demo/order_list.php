<?php
session_start();
error_reporting(E_ALL^E_NOTICE);
// 获取SESSION
$user_params = $_SESSION['user_params'];
$api_host = $_SESSION['api_host'];
if (empty($user_params) || empty($api_host)) {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order List</title>
    <script src="resource/jquery-3.3.1.min.js"></script>
    <script src="resource/bootstrap.min.js"></script>
    <script src="resource/layer/layer.js"></script>
    <link rel="stylesheet" type="text/css" href="resource/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>

<div class="container-fluid">

    <div class="text-center">
        <strong>BlueOceanPay Order List Demo</strong>
    </div>

    <div>
        <form id="form" action="order_list_api.php" class="form-inline">

            <div class="form-group">
                <label for="provider">provider</label>
                <select class="form-control" name="provider" id="provider">
                    <option value="">All</option>
                    <option value="alipay">AliPay</option>
                    <option value="wechat">WeChat</option>
                </select>
            </div>

            <div class="form-group">
                <label for="page">Page</label>
                <input type="text" class="form-control" id="page" name="page" value="1" placeholder="分页参数:第几页 默认值:1">
            </div>

            <div class="form-group">
                <label for="limit">Limit</label>
                <input type="text" class="form-control" id="limit" name="limit" value="10" placeholder="每页条目数 默认值:10">
            </div>

            <div class="form-group">
                <label for="store_id">store_id</label>
                <input type="text" class="form-control" id="store_id" name="store_id" placeholder="门店Id, 登录接口获取的store_id > 0 时填写">
            </div>

            <div class="form-group">
                <label for="trade_state">trade_state</label>
                <select name="trade_state" class="form-control" id="trade_state">
                    <option value="SUCCESS">SUCCESS</option>
                    <option value="REFUND">REFUND</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_time">start_time</label>
                <input type="date" id="start_time" class="form-control" name="start_time" placeholder="按照创建时间查询订单">
            </div>

            <div class="form-group">
                <label for="end_time">end_time</label>
                <input type="date" id="end_time" class="form-control" name="end_time" placeholder="按照创建时间查询订单">
            </div>
            <div class="form-group">
                <button type="button" id="submit" class="btn btn-success">Select</button>
            </div>
        </form>
    </div>

    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <td>Order Sn</td>
                <td>provider</td>
                <td>wallet</td>
                <td>blue_mch_id</td>
                <td>store_id</td>
                <td>body</td>
                <td>out_trade_no</td>
                <td>transaction_id</td>
                <td>total_fee</td>
                <td>discount</td>
                <td>trade_state</td>
                <td>trade_type</td>
                <td>fee_type</td>
                <td>create_time</td>
                <td>finish_time</td>
                <td>time_end</td>
                <td>pay_amount</td>
            </tr>
            </thead>
            <tbody id="table_body">
            </tbody>
        </table>
    </div>
</div>

<script>

    $('#submit').click(function () {
        var url = $('#form').attr('action');

        // 加载动画
        var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

        $.post(url,$('#form').serialize(),function (result) {

            layer.close(index);
            var result = JSON.parse(result);
            layer.msg(result.message);
            var content = "";
            if (result.code == 200){
                $.each(result.data.items,function (index,value) {
                    content += "<tr><td>"+value.id+"</td><td>"+value.sn+"</td><td>"+value.provider+"</td><td>"+value.wallet+"</td><td>"+value.blue_mch_id+"</td><td>"+value.store_id+"</td><td>"+value.body+"</td><td>"+value.out_trade_no+"</td><td>"+value.transaction_id+"</td><td>"+value.total_fee+"</td><td>"+value.discount+"</td><td>"+value.trade_state+"</td><td>"+value.trade_type+"</td><td>"+value.fee_type+"</td><td>"+value.create_time+"</td><td>"+value.finish_time+"</td><td>"+value.time_end+"</td><td>"+value.pay_amount+"</td></tr>";
                })
            }
            $('#table_body').html(content);
        });

    });

</script>

</body>
</html>





