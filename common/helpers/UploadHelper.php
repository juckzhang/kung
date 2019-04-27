<?php
namespace common\helpers;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class CommonHelper
 * @package common\helpers
 */
class UploadHelper
{
    private static $accessKeyId;

    private static $accessKeySecret;

    private static $host;

    /**
     * 获取配置信息
     * @throws InvalidConfigException
     */
    private static function _init()
    {
        $_config = CommonHelper::loadConfig('aliyun',['@common']);

        if(! is_array($_config))
            throw new InvalidConfigException('配置信息错误！');

        static::$accessKeyId = ArrayHelper::getValue($_config,'accessKeyId');

        static::$accessKeySecret = ArrayHelper::getValue($_config,'accessKeySecret');

        static::$host = ArrayHelper::getValue($_config,'host');
    }

    /**
     * 获取签名信息
     * @param string $dir
     * @return array
     * @throws InvalidConfigException
     */
    public static function AliyunSignature($dir = '')
    {
        static::_init();

        list($_sign,$_end) = static::_policy($dir);

        $signature = \base64_encode(\hash_hmac('sha1', $_sign, static::$accessKeySecret, true));

        $response = [];

        $response['accessid'] = static::$accessKeyId;
        $response['host'] = static::$host;
        $response['policy'] = $_sign;
        $response['signature'] = $signature;
        $response['expire'] = $_end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;

        return $response;
    }


    /**
     * 获取policy信息
     * @param $dir
     * @return array
     */
    private static function _policy($dir)
    {
        $conditions = [];

        //获取conditions
        $now = DateHelper::now();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = static::_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);

        $conditions[] = $condition;

        $start = array(0=>'starts-with', 1 => '$key', 2=>$dir);
        $conditions[] = $start;

        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);

        $policy = \json_encode($arr);
        $base64_policy = \base64_encode($policy);

        return [$base64_policy,$end];
//        $signature = \base64_encode(\hash_hmac('sha1', $string_to_sign, static::$accessKeySecret, true));

//        $response = array();
//        $response['accessid'] = static::$accessKeyId;
//        $response['host'] = static::$host;
//        $response['policy'] = $base64_policy;
//        $response['signature'] = $signature;
//        $response['expire'] = $end;
//        //这个参数是设置用户上传指定的前缀
//        $response['dir'] = $dir;
//
//        return $response;
    }

    /**
     * 获取过期时间
     * @param $time
     * @return string
     */
    private static function _iso8601($time)
    {
        $dtStr = date("c", $time);

        $datetime = new \DateTime($dtStr);

        $expiration = $datetime->format(\DateTime::ISO8601);

        $pos = strpos($expiration, '+');

        $expiration = substr($expiration, 0, $pos);

        return $expiration."Z";
    }
}