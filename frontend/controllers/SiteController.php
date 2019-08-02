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
        $translateComponent = \Yii::$app->get('trans');
        $lang = $translateComponent->lang(ArrayHelper::getValue($this->paramData,'lang'), true);
        $text = ArrayHelper::getValue($this->paramData,'text');
        $sourceLang = $translateComponent->detectLanguage($text);
        $target = 'zh-CN';
        if($sourceLang['languageCode'] == 'zh-CN'){
            $target = $lang == 'zh-CN' ? 'en' : $lang;
        }
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
