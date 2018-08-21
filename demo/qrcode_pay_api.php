<?php
session_start();
error_reporting(E_ALL^E_NOTICE);
// 获取SESSION
$user_params = $_SESSION['user_params'];
$api_host = $_SESSION['api_host'];
if (empty($user_params) || empty($api_host)) {
    header("location:login.php");
}

try{

    // 获取Curl
    require_once "../utils/CurlRequest.php";
    // 获取签名类
    require_once "../utils/Signature.php";
    // QRCode
    require_once "../utils/QRCodeUtil.php";
    // 获取配置
    $config_data = (array)require_once "../config.php";

    $params = $_POST;

    $sign_params = array();
    $sign_params['appid'] = (string)$user_params['appid'];
    $sign_params['payment'] = (string)$params['payment'];
    $sign_params['total_fee'] = (int)$params['total_fee'];

    if (!empty($params['discount'])){
        $sign_params['discount'] = (int)$params['discount'];
    }
    if (!empty($params['code']) && $sign_params['payment'] == "micropay"){
        $sign_params['code'] = $params['code'];
    }
    if (!empty($params['out_trade_no'])){
        $sign_params['out_trade_no'] = $params['out_trade_no'];
    }
    if (!empty($params['notify_url'])){
        $sign_params['notify_url'] = $params['notify_url'];
    }

    $sign = Signature::signData($sign_params,$user_params['app_key']);
    $signString = Signature::getSignData($sign_params,$user_params['app_key']);
    $sign_params['sign'] = $sign;

    // API接口
    $login_url = $api_host.'/payment/pay';

    $curl = new \CurlRequest();
    $result_data = $curl->curl($login_url,$sign_params,'POST',false,true,true);

    if ($result_data['status'] == "error"){
        throw new \Exception($result_data['message']);
    }

    $data = json_decode($result_data['data'],true);

    $QRCode = new \QRCodeUtil();

    $qrcode_name = $QRCode->create($data['data']['qrcode']);

    $result_data = [
        'qrcode_url'=>'qrcode/'.$qrcode_name,
        'request_params'=>$sign_params,
        'response_params'=>$data['data'],
        'signString'=>$signString
    ];

    echo json_encode([
        'code'=>$data['code'],
        'message'=>$data['message'],
        'data'=>$result_data
    ]);


}catch (\Exception $exception){

    echo json_encode([
        'code'=>'400',
        'message'=>$exception->getMessage(),
    ]);
}




