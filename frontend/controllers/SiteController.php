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
        $ret = UserService::getService()->login($this->paramData);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess(CodeConstant::SUCCESS, $ret);
    }

    public function actionTranslate()
    {
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $text = ArrayHelper::getValue($this->paramData,'text');
        $translateComponent = \Yii::$app->get('trans');
        $sourceLang = $translateComponent->detectLanguage($text);
        $target = $sourceLang['languageCode'] == 'zh-CN' ? $translateComponent->lang($lang) : 'zh_CN';
        $res = $translateComponent->translate($text, $target);
        $toText = $res['text'];
        if($target == 'zh_CN'){
            $pinyin = \Yii::$app->get('pinyin')->sentence($toText, PINYIN_TONE);
        }
        $ret = [
            'from_lang' => $translateComponent->lang($res['source']),
            'from_text' => $res['input'],
            'to_lang' => $translateComponent->lang($target),
            'to_text' => $toText,
            'to_text_pinyin' => $pinyin,
        ];

        return $this->returnSuccess($ret);
    }
}
