<?php
namespace common\components;
use yii\helpers\Json;
use yii\helpers\Html;

class Translation
{
    public $key;

    const API_URL = 'https://www.googleapis.com/language/translate/v2';

    public function translate($source, $target, $text)
    {
        return $this->getResponse($this->getRequest('', $text, $source, $target));
    }

    public function discover()
    {
        return $this->getResponse($this->getRequest('languages'));
    }

    public function detect($text)
    {
        return $this->getResponse($this->getRequest('detect', $text));
    }

    protected function getRequest($method, $text = '', $source = '', $target = '')
    {
        $request = self::API_URL . '/' . $method . '?' . http_build_query(
                [
                    'key' => $this->key,
                    'source' => $source,
                    'target' => $target,
                    'q' => Html::encode($text),
                ]
            );
        return $request;
    }

    protected function getResponse($request)
    {
        $response = file_get_contents($request);
        return Json::decode($response, true);
    }
}