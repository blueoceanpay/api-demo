<?php
session_start();
/**
 * Created by PhpStorm.
 * User: YUN
 * Date: 2018/6/1
 * Time: 10:32
 */

class Language
{
    public static function lang($message){
        $la = $_SESSION['language'];
        if (empty($la)){
            $la = 'cn';
        }
        $languages = (array)require "language/".$la.".php";
//        var_dump($languages);
        $data = $languages[$message];
        if (empty($data)){
            return $message;
        }
        return $data;
    }
}