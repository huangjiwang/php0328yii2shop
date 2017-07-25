<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $zddl;
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['zddl','boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'zddl'=>'自动登录'
        ];
    }

    public function login()
    {
        //1.1 通过用户名查找用户
        $admin = Admin::findOne(['username'=>$this->username]);
        if($admin){
            if(\Yii::$app->security->validatePassword($this->password,$admin->password)){
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
        return false;
    }

}