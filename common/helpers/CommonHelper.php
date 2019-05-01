<?php
namespace common\helpers;
use \yii\base\InvalidConfigException;
use \yii\base\InvalidParamException;
use \yii\helpers\ArrayHelper;

class CommonHelper {

    public static function loadConfig($file,$configPath = [])
    {
        $_configPaths = ['@common', '@backend'];
        if( ! empty($configPath)) $_configPaths = $configPath;
        $_config = [];

        $file = ($file === '') ? 'main' : str_replace('.php', '', $file);

        foreach ($_configPaths as $_path)
        {
            foreach ([$file, YII_ENV .'/'.$file] as $location)
            {
                $_filePath = \yii::getAlias($_path.'/config/'.$location.'.php');

                if ( ! file_exists($_filePath))
                {
                    continue;
                }

                $_configSection = include ($_filePath);

                if ( ! is_array($_configSection))
                    throw new InvalidConfigException('Your '.$_filePath.' file does not appear to contain a valid configuration array.');

                $_config = ArrayHelper::merge($_config, $_configSection);
            }
        }

        return $_config;
    }

    public static function randString($length = 6,$source = '')
    {
        $source = is_string($source) && $source !== '' ?
            $source : 'QWERTYUIPASDFGHJKLZXCVBNM123456789qwertyuipasdfghjklzxcvbnm';

        $_strLength = strlen($source);

        if($length > $_strLength)
            throw new InvalidParamException('Param error for $length too long ' . $length);

        $_str = '';
        for($i = 0; $i <$length; ++$i )
        {
            srand(time());
            $_random = mt_rand(0,$_strLength - 1);
            $_str .= $source[$_random];
        }

        return $_str;
    }

    public static function isMobile($mobile)
    {
        return preg_match('/^1[3|4|5|8|7][0-9]\d{8}$/',$mobile) ? true : false;
    }

    public static function isMobileClient()
    {
        $_clientType = static::clientType();
        if($_clientType !== 3) return true;
        return false;
    }

    public static function clientType()
    {
        static $CLIENT_TYPE_ANDROID = 1;
        static $CLIENT_TYPE_IOS     = 2;
        static $CLIENT_TYPE_PC      = 3;
        $_clientType = $CLIENT_TYPE_PC;

        $header = \Yii::$app->request->headers;
        $userAgent = strtolower($header->get('user-agent'));

        //判断是否是安卓
        if(strpos($userAgent,'android'))
            $_clientType = $CLIENT_TYPE_ANDROID;

        //判断是否是ios
        if(strpos($userAgent,'iphone'))
            $_clientType = $CLIENT_TYPE_IOS;

        return $_clientType;
    }

    public static function userType($id){
        $type = '';
        switch ($id){
            case 1:
                $type = '普通会员';
                break;
            case 2:
                $type = '认证会员';
                break;
            case 3:
                $type = '合作会员';
                break;
            default;break;
        }
        return $type;
    }

    public static function getOrderno()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));

        return 'zuiying'.$orderSn;
    }
}