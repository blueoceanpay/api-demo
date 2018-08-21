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
    // 获取配置
    $config_data = (array)require_once "../config.php";
    // 获取参数
    $params = $_POST;

    $sign_params = array();
    $sign_params['appid'] = (string)$user_params['appid'];

    if (!empty($params['end_time'])){
        $sign_params['end_time'] = $params['end_time'];
    }
    if (!empty($params['limit'])){
        $sign_params['limit'] = $params['limit'];
    }
    if (!empty($params['page'])){
        $sign_params['page'] = $params['page'];
    }
    if (!empty($params['provider'])){
        $sign_params['provider'] = $params['provider'];
    }
    if (!empty($params['start_time'])){
        $sign_params['start_time'] = $params['start_time'];
    }
    if (!empty($params['store_id'])){
        $sign_params['store_id'] = $params['store_id'];
    }
    if (!empty($params['trade_state'])){
        $sign_params['trade_state'] = $params['trade_state'];
    }

    $sign = Signature::signData($sign_params,$user_params['app_key']);
    $sign_params['sign'] = $sign;

    // API接口
    $api_url = $api_host.'/order/list';

    $curl = new \CurlRequest();
    $result_data = $curl->curl($api_url,$sign_params,'POST',false,true,true);

    if ($result_data['status'] == "error"){
        throw new \Exception($result_data['message']);
    }

    $data = json_decode($result_data['data'],true);

    echo json_encode([
        'code'=>$data['code'],
        'message'=>$data['message'],
        'data'=>$data['data']
    ]);


}catch (\Exception $exception){
    echo json_encode([
        'code'=>400,
        'message'=>$exception->getMessage()
    ]);
}












