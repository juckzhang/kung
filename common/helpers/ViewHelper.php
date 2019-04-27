<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 2017/3/22
 * Time: 下午11:30
 */
namespace common\helpers;
use yii;
class ViewHelper
{
    public static function truncate_utf8_string($string, $length, $etc = '... ...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else    
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }

    //防止刷点击数
    public static function RefreshHit($id){
        $ip = Yii::$app->request->userIP;
        $parameter = Yii::$app->request->getUrl();
        $allowTime = \Yii::$app->params['clicktime'];
        $allowT = md5($ip.$parameter.$id);
        if(!isset($_SESSION[$allowT])){
            $refresh = true;
            $_SESSION[$allowT] = time();
        }elseif(time() - $_SESSION[$allowT]>$allowTime){
            $refresh = true;
            $_SESSION[$allowT] = time();
        }else{
            $refresh = false;
        }

        return $refresh;
    }
}