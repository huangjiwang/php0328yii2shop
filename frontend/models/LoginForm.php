<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $code;//图像验证码
    public $zddl;
    public $username;
    public $password;
    public function rules()
{
return [
    [['username','password','code'],'required'],
    ['zddl','boolean'],
    [['code'], 'captcha'],
    ];
}

public function attributeLabels()
{
return [
    'username'=>'用户名',
    'password'=>'密码',
    'zddl'=>'记住我',
    'code'=>'验证码'
    ];
}

public function login()
{
//1.1 通过用户名查找用户
    $admin=Member::findOne(['username'=>"$this->username"]);
    //var_dump($admin);exit;
        if($admin){
            //var_dump(\Yii::$app->security->validatePassword($this->password,$admin->password_hash));exit;
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //密码正确.可以登录
                //2 登录(保存用户信息到session)
                \Yii::$app->user->login($admin,$this->zddl?7*24*3600:0);
                return true;
            }else{
                //密码错误.提示错误信息
                $this->addError('password','密码错误');
            }

        }else{
                //用户不存在,提示 用户不存在 错误信息
                $this->addError('username','用户名不存在');
        }
    }

}