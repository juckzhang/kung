<?php
namespace common\events;

use yii\base\Event;

class MediaEvent extends Event{
    public $videoId = null;
    public $userId = null;
    public $operationId = null;
}