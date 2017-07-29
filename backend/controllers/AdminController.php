<?php

namespace backend\controllers;


use backend\models\Admin;
use backend\models\LoginForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
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

    public function actionIndex()
    {
        $model =Admin::find()->all();
        //var_dump($model2);exit;
        return $this->render('index',['model'=>$model]);
    }

//添加用户
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

               //var_dump($admin->juese,$admin->id);exit;
                //用户与角色的关联
                $authManager=\Yii::$app->authManager;
                //获取角色
                $authManager->getRole($admin->juese);
                //判断是否是个数组
                if(is_array($admin->juese)){
                    //如果是个数组就遍历依次遍历出来添加
                        foreach($admin->juese as $js){
                            $juese=$authManager->getRole($js);
                            $authManager->assign($juese,$admin->id);
                    }
                }
                \Yii::$app->session->setFlash('success','添加成功');
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
        //取消角色和权限的关联
        $authManager=\Yii::$app->authManager;
       //根据用户ID 查角色
        $juese=$authManager->getRolesByUser($admin->id);
        //表单权限回显
        $admin->juese=ArrayHelper::map($juese,'name','name');
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
                //修改用户角色
                //用户与角色的关联
                $authManager=\Yii::$app->authManager;
                //获取角色
                $authManager->getRole($admin->juese);
                //取消全部关联
                $authManager->revokeAll($admin->id);
                //判断是否是个数组
                if(is_array($admin->juese)){
                    //如果是个数组就遍历依次遍历出来添加
                    foreach($admin->juese as $js){
                        $juese=$authManager->getRole($js);
                        $authManager->assign($juese,$admin->id);
                    }
                }
                //提示操作
                \Yii::$app->session->setFlash('success','修改成功');
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
        $authManage=\Yii::$app->authManager;
        //根据用户ID 查角色
        $juese=$authManage->getRolesByUser($admin->id);
        //var_dump($juese);exit;
        if($juese==null){
            throw new NotFoundHttpException('角色不存在');
        }
        //删除角色
        $authManage->revokeAll($id);

        //删除用户
        $admin->delete();
        //提示操作
        \Yii::$app->session->setFlash('success','删除成功');
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
