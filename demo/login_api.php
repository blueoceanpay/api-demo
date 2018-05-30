<?php
session_start();
error_reporting(E_ALL^E_NOTICE);

try{

    // Curl请求类
    require_once "..\utils\CurlRequest.php";
    // 获取配置
    $config_data = (array)require_once "..\config.php";
    // 获取参数
    $data = $_POST;

    if (in_array($data['api_host'],$config_data['api_host'])){
        $api_host = $data['api_host'];
    }else{
        // 默认香港
        $api_host = $config_data['api_host']['hong_kong'];
    }
    $_SESSION['api_host'] = $api_host;

    // 获取API
    $login_url = $api_host.'/user/login';

    $curl = new \CurlRequest();
    $result_data = $curl->curl($login_url,$data,'POST',false,true,true);

    if ($result_data['status'] == "error"){
        throw new \Exception($result_data['message']);
    }

    if ($result_data['status'] == 'success'){
        $re_data = json_decode($result_data['data'],true);
        //var_dump($re_data);exit();
        if ($re_data['code'] == 200){
            $data = [
                'code'=>'200',
                'message'=>$re_data['message'],
                'data'=>''
            ];

            $_SESSION['user_params'] = $re_data['data'];

        }else{
            $data = [
                'code'=>'400',
                'message'=>$re_data['message']
            ];
        }
    } else{
        $data = [
            'code'=>400,
            'message'=>'数据访问失败',
        ];
    }

    echo json_encode($data);

}catch (\Exception $exception){
    echo json_encode([
        'code'=>400,
        'message'=>$exception->getMessage()
    ]);
}


