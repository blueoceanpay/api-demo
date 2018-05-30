<?php
/**
 * Created by PhpStorm.
 * User: YUN
 * Date: 2018/5/25
 * Time: 16:50
 * 签名类
 */

//namespace utils;


class Signature
{
    /**
     * 参数数组数据签名
     * @param array $data 参数
     * @param string $key 密钥
     * @return string 签名
     */
    public static function signData($data,$key){
        $signString = self::getSignData($data,$key);
        return strtoupper(md5($signString));
    }

    public static function getSignData($data,$key){
        $ignoreKeys = ['sign', 'key'];
        ksort($data);
        $signString = '';
        foreach ($data as $k => $v) {
            if (in_array($k, $ignoreKeys)) {
                unset($data[$k]);
                continue;
            }
            $signString .= "{$k}={$v}&";
        }
        $signString .= "key={$key}";
        return $signString;
    }


}