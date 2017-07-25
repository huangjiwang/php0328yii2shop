<?php

namespace backend\controllers;


use backend\models\Admin;
use backend\models\LoginForm;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    //登录
    public function actionLogin()
    {
        //1 认证(检查用户的账号和密码是否正确)
        $model = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->login()){
                //登录成功
                //var_dump($model->password);exit;
                \Yii::$app->session->setFlash('success','登录成功');
                $admin=Admin ::findOne(['username'=>$model->username]);
                //var_dump($admin->username);exit;
                $admin->last_login_time=date('Y-m-d H:i:s',time());
                $admin->last_login_ip=$_SERVER['REMOTE_ADDR'];
                //var_dump( $admin->auth_key);exit;
                $admin->save(false);
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

public function actionAaa(){
    var_dump(\Yii::$app->user->identity->username);
}
    public function actionIndex()
    {
        $model =Admin::find()->all();
        //var_dump($model2);exit;
        return $this->render('index',['model'=>$model]);
    }


    public function actionAdd(){
        //实列化一个表单
        $admin = new Admin();
        $requerst=new Request();
        //判断是否以post方式提交
        if($requerst->isPost){
            $admin->load($requerst->post());
            //var_dump();exit;
            //验证数据
            if($admin->validate()) {
                $admin->username = $admin->username;
                $admin->password = \Yii::$app->security->generatePasswordHash($admin->password);
                $admin->auth_key=\Yii::$app->security->generateRandomString();
                $admin->save(false);
                return $this->redirect(['admin/index']);
            }else{
                //验证或保存失败
                var_dump($admin->getErrors());exit;
            }
        }
        //调用视图并传值
        return $this->render('add',['model'=>$admin]);
    }
//修改
    public function actionEdit($id){
        //实列化一个表单
        $admin=Admin::findOne(['id'=>$id]);
        $requerst=new Request();
        //判断是否以post方式提交
        if($requerst->isPost){
            $admin->load($requerst->post());
            //var_dump();exit;
            //验证数据
            if($admin->validate()) {
                $admin->username = $admin->username;
                $admin->password = \Yii::$app->security->generatePasswordHash($admin->password);
                $admin->save(false);
                return $this->redirect(['admin/index']);
            }else{
                //验证或保存失败
                var_dump($admin->getErrors());exit;
            }
        }
        //调用视图并传值
        return $this->render('add',['model'=>$admin]);
    }
//删除
    public function actionDelete($id){
        $admin=Admin::findOne(['id'=>$id]);
        $admin->delete();
        //var_dump($brand->status);exit;
        return $this->redirect(['admin/index']);
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }

    //用户修改自己信息
    public function actionEdituser($id)
    {
        //实列化一个表单
        $admin = Admin::findOne(['id' => $id]);
        $admin->scenario = Admin::SCENARIO_EDITUSER;
        $requerst = new Request();
        //判断是否以post方式提交
        if ($requerst->isPost) {
            $admin->load($requerst->post());
            if ($admin->validate()) {
                if (\Yii::$app->security->validatePassword($admin->jpassword, $admin->password)) {
                        if (\Yii::$app->security->validatePassword($admin->xpassword, $admin->password)) {
                            \Yii::$app->session->setFlash('success','新密码不能与旧密码一样');
                        }else {
                            if ($admin->xpassword == $admin->qrpassword) {
                                $admin->username = $admin->username;
                                $admin->password = \Yii::$app->security->generatePasswordHash($admin->xpassword);
                                $admin->save(false);
                                return $this->redirect(['admin/index']);
                            }else{
                                \Yii::$app->session->setFlash('success', '两次密码输入不一致');
                            }
                        }
                    }else {
                    \Yii::$app->session->setFlash('success','旧密码输入错误');
                    }
                }
            }

            return $this->render('edituser', ['model' => $admin]);
        }



}
