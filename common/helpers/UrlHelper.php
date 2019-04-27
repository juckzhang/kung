<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 12/04/2017
 * Time: 4:48 PM
 */

namespace common\helpers;

use common\constants\Constant;

class UrlHelper {
    public static function make($path, array $params = null, $base = Constant::WEB_BASE) {
        $full_path = $base.$path;
        $param_str = '';
        foreach ($params as $k=>$v) {
            $param_str = $param_str.$k.'='.$v.'&';
        }
        $r = $full_path.'?'.$param_str;
        return substr($r, 0, strlen($r)-1);
    }
}