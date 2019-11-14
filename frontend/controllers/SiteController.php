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
        $userLevel = ArrayHelper::getValue($this->paramData,'user_level');
        $ret = MediaService::getService()->recommendList($lang, $userLevel);
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
        $ret = [
            'from_lang' => $translateComponent->lang($res['source']),
            'from_text' => $res['input'],
            'to_lang' => $translateComponent->lang($target),
            'to_text' => $toText,
        ];
        if($target == 'zh-CN'){
            $ret['to_text_pinyin'] = \Yii::$app->get('pinyin')->sentence($toText, PINYIN_TONE);
        }

        return $this->returnSuccess($ret);
    }
}
