<?php
namespace frontend\controllers;

use frontend\models\Member;
use yii\web\Controller;

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
    public function actionLogin(){
        //实例化一个模型
        $model=new Member();

        //跳转到试图
        return $this->render('login',['model'=>$model]);
    }

}















