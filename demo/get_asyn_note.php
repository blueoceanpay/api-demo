<?php
/**
 * Created by PhpStorm.
 * User: YUN
 * Date: 2018/5/31
 * Time: 10:31
 * 获取到系统的异步消息通知
 */

//echo $host = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];//paymentdemo.xinyun.com:8080

$params = $_POST;
file_put_contents('qrcode/asyn_data.txt',json_encode($params));

echo "SUCCESS";




