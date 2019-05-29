<?php
namespace frontend\controllers;

use common\constants\CodeConstant;
use common\helpers\CommonHelper;
use common\services\MediaService;
use frontend\services\UserService;
use yii\helpers\ArrayHelper;

class SiteController extends BaseController
{
    // 首页推荐
    public function actionRecommendList()
    {
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $ret = MediaService::getService()->recommendList($lang);
        return $this->returnSuccess($ret);
    }

    // 登陆
    public function actionLogin()
    {
        $userService = UserService::getService();
        $params = $this->parseParam();

        $ret = $userService->login($params);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess(CodeConstant::SUCCESS, $ret);
    }

    public function actionTranslate()
    {
        $text = ArrayHelper::getValue($this->paramData,'text');
        $target  = 'zh_CN';
        $res = \Yii::$app->get('trans')->translate($text, $target);
        $toText = $res['text'];
        if($target == 'zh_CN'){
            $pinyin = \Yii::$app->get('pinyin')->sentence($toText, PINYIN_TONE);
            $toText .= ' [' . $pinyin . ']';
        }
        $ret = [
            'from_lang' => \Yii::$app->get('trans')->lang($res['source']),
            'from_text' => $res['input'],
            'to_lang' => 'zh_CN',//\Yii::$app->get('trans')->lang($target),
            'to_text' => $toText,
        ];

        return $this->returnSuccess($ret);
    }
}
