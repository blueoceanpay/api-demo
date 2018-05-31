<?php
/**
 * Created by PhpStorm.
 * User: YUN
 * Date: 2018/5/25
 * Time: 16:47
 */

//namespace utils;


class CurlRequest
{

    /**
     * 步骤：
     * 初始化连接句柄；
     * 设置CURL选项；
     * 执行并获取结果；
     * 释放CURL连接句柄。
     */
    public static function curl($url,$param=array(),$method="GET",$cookie=false,$ssl=false,$is_json=false){
        // 代理信息
        //user_agent,请求代理信息
        $user_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0";

        // 1. 初始化
        $ch = curl_init();

        // 2. 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //location属性就代表重定向的地址。如果curl爬取过程中，设置CURLOPT_FOLLOWLOCATION为true，则会跟踪爬取重定向页面，否则，不会跟踪重定向页面。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,$user_agent);

        if ($is_json){
            $param = json_encode($param,JSON_UNESCAPED_UNICODE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: ' . strlen($param)
                )
            );
        }

        //处理POST
        if ($method == "POST" OR $method == "post"){
            //设置post方式提交
            curl_setopt($ch, CURLOPT_POST, 1);
            //设置post数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

        }
        //Cookie
        if ($cookie){
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        //ssl相关
        if($ssl){
            //不验证，相信微信服务器
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            //检查服务器ssl证书中是否存在一个公用名（common name）
            //curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,1);
        }

        //referer头,请求来源
        curl_setopt($ch,CURLOPT_AUTOREFERER,true);
        //是否处理响应头
        curl_setopt($ch,CURLOPT_HEADER,false);
        //curl_exec()是否返回响应结果
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error_msg = curl_error($ch);
        // 4. 释放curl句柄
        curl_close($ch);



        if($output === FALSE ){
            return array("status"=>"error","message"=>$error_msg);
        }
        return array("status"=>"success","message"=>"访问成功","data"=>$output,"info"=>$info);
    }


}