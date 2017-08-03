<?php
namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;
use frontend\components\AliyunSms;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;


class MemberController extends Controller{

    public $layout=false;
    //关闭验证
    public $enableCsrfValidation=false;
    //注册
    public function actionRegist(){
        //实例化一个模型
        $model=new Member();
        if($model->load(\Yii::$app->request->post()) &&  $model->validate()){
            //var_dump($model->repassword,$model->password_hash);exit;
            //判断两次密码是否一致
           // if($model->repassword == $model->password_hash){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['member/login']);
           // }else{
                //\Yii::$app->session->setFlash('success','两次密码不一致');
           // }
        }
        //跳转到试图
        return $this->render('regist',['model'=>$model]);
    }
    public function actionLogin()
    {
        //实例化一个模型
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post(),'LoginForm')) {
           if( $model->validate()&&$model->login()){
               //登录成功
               //将cookie中的数据加给数据库
               $cookies = \Yii::$app->request->cookies;
               $cart = $cookies->get('cart');
               if($cart) {
                   $carts = unserialize($cart->value);
                   //var_dump( $model->username);exit;
                   $user = Member::findOne(['username' => $model->username]);
                   //var_dump($user);exit;
                   foreach (array_keys($carts) as $goods_id) {
                       $member_id = $user->id;
                       //var_dump($goods_id);exit;
                       $model = Cart::find()->where(['=', 'member_id', $member_id])->andWhere(['=', 'goods_id', $goods_id])->one();
                       if ($model) {
                           $model->amount += $carts[$goods_id];
                           $model->save();

                       } else {
                           $model = new Cart();
                           $model->goods_id = $goods_id;
                           $model->amount = $carts[$goods_id];
                           $model->member_id = $member_id;
                           $model->save();
                       }
                       //清除cookie中的cart数据
                       $cookies = \Yii::$app->response->cookies;
                       $cookies->remove('cart');

                   }
               }    //跳转到试图
               return $this->redirect('http://www.yii2.com:8080/index.php?r=home/index');
           }else{
              var_dump($model->getErrors());exit;
           }
        }
        return $this->render('login', ['model' => $model]);
    }

    //测试发送短信功能
    public function actionTestSms()
    {
        //短信验证
        $code = rand(1000,9999);
        $tel = '17774471698';
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();

    }


}















