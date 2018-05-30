<?php
session_start();
error_reporting(E_ALL^E_NOTICE);

$user_params = $_SESSION['user_params'];
$api_host = $_SESSION['api_host'];
if (empty($user_params) || empty($api_host)) {
    header("location:login.html");
}

try{

    // 获取Curl
    require_once "..\utils\CurlRequest.php";
    // 获取签名类
    require_once "..\utils\Signature.php";
    // QRCode
    require_once "..\utils\QRCodeUtil.php";
    // 获取配置
    $config_data = (array)require_once "..\config.php";

    $params = $_POST;

    $sign_params = array();
    $sign_params['appid'] = (string)$user_params['appid'];
    if (!empty($params['sn'])){
        $sign_params['sn'] = (string)$params['sn'];
    }
    if (!empty($params['out_trade_no'])){
        $sign_params['out_trade_no'] = (string)$params['out_trade_no'];
    }
    if (!empty($params['refund_fee'])){
        $sign_params['refund_fee'] = (int)$params['refund_fee'];
    }
    if (!empty($params['refund_desc'])){
        $sign_params['refund_desc'] = (string)$params['refund_desc'];
    }
    if (!empty($params['password'])){
        $sign_params['password'] = (string)$params['password'];
    }

    $sign = Signature::signData($sign_params,$user_params['app_key']);
    $sign_string = Signature::getSignData($sign_params,$user_params['app_key']);
    $sign_params['sign'] = $sign;

    // API接口
    $refund_url = $api_host.'/payment/refund';

    $curl = new \CurlRequest();
    $result_data = $curl->curl($refund_url,$sign_params,'POST',false,true,true);

    if ($result_data['status'] == "error"){
        throw new \Exception($result_data['message']);
    }

    $data = json_decode($result_data['data'],true);

    echo json_encode([
        'code'=>$data['code'],
        'message'=>$data['message'],
        'data'=>[
            'request_params'=>$sign_params,
            'response_params'=>$data['data'],
            'sign_string'=>$sign_string
        ]
    ]);

}catch (\Exception $exception){
    echo json_encode([
        'code'=>'400',
        'message'=>$exception->getMessage(),
    ]);
}










