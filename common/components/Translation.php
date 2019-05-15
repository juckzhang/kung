<?php
namespace common\components;

use Google\Cloud\Translate\TranslateClient;
use yii\base\Component;

class Translation extends Component
{
    public $projectId;
    private $_client;

    public function init()
    {
        if(!($this->_client instanceof TranslateClient)){
            $this->_client = new TranslateClient(['projectId' => $this->projectId]);
        }
    }

    public function translate($text, $target)
    {
        return $this->_client->translate($text, [
            'target' => $target
        ]);
    }
}