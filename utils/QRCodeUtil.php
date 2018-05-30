<?php
/**
 * Created by PhpStorm.
 * User: YUN
 * Date: 2018/5/28
 * Time: 10:39
 */

class QRCodeUtil
{
    public function create($data){
        require_once "phpqrcode/qrlib.php";
        $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'demo'.DIRECTORY_SEPARATOR.'qrcode'.DIRECTORY_SEPARATOR;

        if (!file_exists($PNG_TEMP_DIR)){
            mkdir($PNG_TEMP_DIR);
        }

        $filename = $PNG_TEMP_DIR.md5(uniqid()).'.png';
        //html PNG location prefix
        //$PNG_WEB_DIR = 'temp/';

        \QRCode::png($data, $filename, 'Q', 4, 2);

        // URL
        return basename($filename);
    }
}