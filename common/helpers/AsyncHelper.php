<?php
namespace common\helpers;
use \common\services\queues\QueueFactory;
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/23
 * Time: 16:38
 */
class AsyncHelper extends \yii\helpers\ArrayHelper
{
    /**
     * 队列中插入数据
     * @param $queueName
     * @param array $data
     * @param int $delay
     * @return mixed
     */
    public static function push($queueName,array $data, $delay = 0)
    {
        return QueueFactory::get($queueName)->push($data,$delay);
    }
}