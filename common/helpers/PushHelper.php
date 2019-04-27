<?php
namespace common\helpers;

use backend\models\mongodb\UserLoginModel;
use common\components\notice\AndroidNotice;
use common\components\notice\IOSNotice;
use yii\base\InvalidParamException;

class PushHelper {

    private static $handler = [];

    /**
     * 初始化通知对象
     * @param $clientType
     */
    private static function init($clientType)
    {
        if( ! isset(static::$handler[$clientType])){
            if($clientType == UserLoginModel::CLIENT_TYPE_ANDROID)
                static::$handler[$clientType] = new AndroidNotice();

            elseif($clientType == UserLoginModel::CLIENT_TYPE_IOS)
                static::$handler[$clientType] = new IOSNotice();

            else
                throw new InvalidParamException('the client type error $clientType=' . $clientType);
        }
    }

    /**
     * 单播
     * @param $clientType
     * @param $deviceToken
     * @param $content
     * @param int $startTime
     * @param int $endTime
     * @return mixed
     */
    public static function unitCast($clientType,$deviceToken,$content,$startTime = 0,$endTime = 0)
    {
        static::init($clientType);
        $handler = static::$handler[$clientType];

        return $handler->unitCast($deviceToken,$content,$startTime,$endTime);
    }

    /**
     * @param $clientType
     * @param array $deviceToken
     * @param $content
     * @param int $startTime
     * @param int $endTime
     * @return mixed
     */
    public static function listCast($clientType,array $deviceToken,$content,$startTime = 0,$endTime = 0)
    {
        static::init($clientType);
        $handler = static::$handler[$clientType];

        return $handler->listCast($deviceToken,$content,$startTime,$endTime);
    }

    /**广播
     * @param $clientType
     * @param $content
     * @param int $startTime
     * @param int $endTime
     * @return mixed
     */
    public static function broadCast($clientType,$content,$startTime = 0,$endTime = 0)
    {
        static::init($clientType);
        $handler = static::$handler[$clientType];

        return $handler->broadCast($content,$startTime,$endTime);
    }
}