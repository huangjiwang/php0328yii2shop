<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Member extends ActiveRecord{

    public $repassword;
    public $code;//图像验证码
    public $smsCode;//短信验证码
    public function rules()
    {
        return [
            [['username','password_hash','repassword','code','email','tel'],'required'],
            [['code'], 'captcha'],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            ['email','email'],
            ['repassword','compare','compareAttribute'=>'password_hash','message'=>'两次密码不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'repassword'=>'确认密码',
            'tel'=>'电话',
            'email'=>'邮箱',
            'code'=>'验证码',

        ];
    }
}