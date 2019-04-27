<?php
namespace common\events;

use yii\base\Event;

class VideoEvent extends Event{
    public $videoId = null;
    public $userId = null;
    public $operationId = null;
}