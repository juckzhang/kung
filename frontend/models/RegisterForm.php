<?php
namespace frontend\models;

use common\models\mysql\UserModel;
use common\services\UserService;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class RegisterForm extends Model
{
    public $mobile;
    public $password;
    public $repeatPassword;
    public $code;
    public $agree = true;
    public $nick_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile', 'password','repeatPassword','code','nick_name'], 'required','message'=>'值不能为空！'],
            [['mobile', 'password','repeatPassword','code','nick_name'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            [['code'],'validateCode','message' => '验证码不正确！'],
            // rememberMe must be a boolean value
            [['password', 'repeatPassword'],'string','length' => [6,16],'message' => '密码必须保证6-16位！'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password','message'=>'两次输入的密码不一致！'],
            [['mobile'],'unique','filter' => ['status' => UserModel::STATUS_ACTIVE],'targetClass' => UserModel::className(),'targetAttribute' => 'mobile','message' => '手机号已被注册！'],
        ];
    }

    /**
     * 校验用户验证码
     * @param $attribute
     * @param $params
     */
    public function validateCode($attribute,$params)
    {
        if (!$this->hasErrors()) {
            if(! UserService::getService()->validateCode($this->mobile,$this->$attribute))
                $this->addError($attribute, '验证码错误！');
        }
    }

    /**
     * 注册用户，完成登陆
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) return false;

        //注册用户
        $user = new UserModel();
        if ($user->add(['nick_name' => $this->nick_name, 'mobile' => $this->mobile, 'password' => $this->password]))
            return Yii::$app->user->login($user, 3600 * 24 * 30);

        $this->addError('mobile', '注册失败');
        return false;
    }

    /*
     * 修改密码
     */
    public function reset()
    {
        if ($this->validate()) return false;

        //修改密码
        $user = new UserModel();
        if ($user->updateAll(['password' => Yii::$app->security->generatePasswordHash($this->password)],['mobile' => $this->mobile]))
            return Yii::$app->user->login($user, 3600 * 24 * 30);

        $this->addError('mobile', '修改失败');
        return false;

    }
}
