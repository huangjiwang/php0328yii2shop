<?php
namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\filters\RbacFilter;

class RbacController extends Controller
{
//权限的增删改查
    //添加权限
    public function actionAddPermission(){
        $model=new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        //接受参数和验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager=\Yii::$app->authManager;
            //创建权限
            $permission=$authManager->createPermission($model->name);
            //赋值
            $permission->description=$model->description;
            //保存到数据表
            $authManager->add($permission);

            //提示操作
            \Yii::$app->session->setFlash('success','添加成功');
            //添加成功跳转到显示的方法
            return $this->redirect(['rbac/index-permission']);
        }

        //跳转到添加页面
        return $this->render('add-permission',['model'=>$model]);
    }
    //展示权限
    public function actionIndexPermission(){
        //获取所有权限
        $authManager=\Yii::$app->authManager;
        $models=$authManager->getPermissions();

        return $this->render('index-permission',['model'=>$models]);
    }
    //修改权限
    public function actionEditPermission($name){
        //检查权限是否存在
        $authManage=\Yii::$app->authManager;
        $permission=$authManage->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //权限存在
        //创建一个模型实现修改功能
        $model=new PermissionForm();
        if(\Yii::$app->request->post()){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //将表单数据赋值给权限
                $permission->name=$model->name;
                $permission->description=$model->description;
                //修改权限
                $authManage->update($name,$permission);

                //提示操作
                \Yii::$app->session->setFlash('success','修改成功');
                //添加成功跳转到显示的方法
                return $this->redirect(['rbac/index-permission']);
            }
        }else{
            $model->name=$permission->name;
            $model->description=$permission->description;
        }



        return $this->render('add-permission',['model'=>$model]);
    }

    //删除权限
    public function actionDelPermission($name){
        //echo 11;exit;
        $authManage=\Yii::$app->authManager;
        $permission=$authManage->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //删除权限
        $authManage->remove($permission);
        //提示操作
        \Yii::$app->session->setFlash('success','删除成功');
        //添加成功跳转到显示的方法
        return $this->redirect(['rbac/index-permission']);
    }


//角色的增删改查
    //添加角色
    public function actionAddRole(){
        //创建一个表单模型
        $model=new RoleForm();
        //接受参数和验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //创建和保存角色
            $authManager=\Yii::$app->authManager;
            $role=$authManager->createRole($model->name);
            $role->description=$model->description;
            $authManager->add($role);
            //给角色赋予权限
            if(is_array($model->permissions)){
                foreach($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){$authManager->addChild($role,$permission);}
                }
            }
            //提示操作
            \Yii::$app->session->setFlash('success','添加成功');
            //添加成功跳转到显示的方法
            return $this->redirect(['rbac/index-role']);
        }
        //跳转到添加页面
        return $this->render('add-role',['model'=>$model]);
    }

    //展示角色列表
    public function actionIndexRole(){
        $authManager=\Yii::$app->authManager;
        $models=$authManager->getRoles();

        return $this->render('index-role',['model'=>$models]);
    }

    //修改角色
    public function actionEditRole($name){
        $model=new RoleForm();
        //取消角色和权限的关联
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        //表单权限回显
        $permissions=$authManager->getPermissionsByRole($name);
        $model->name=$role->name;
        $model->description=$role->description;
        $model->permissions=ArrayHelper::map($permissions,'name','name');

        //接受参数和验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //创建和保存角色
            $authManager=\Yii::$app->authManager;
            $role=$authManager->createRole($model->name);
            $role->description=$model->description;
            $authManager->update($name,$role);
            //给角色赋予权限
            if(is_array($model->permissions)){
                foreach($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        //全部取消关联
                        $authManager->removeChildren($role);
                        //在依次关联
                        $authManager->addChild($role,$permission);
                    }
                }
            }
            //提示操作
            \Yii::$app->session->setFlash('success','修改成功');
            //添加成功跳转到显示的方法
            return $this->redirect(['rbac/index-role']);
        }
        return $this->render('add-role',['model'=>$model]);
    }

    //删除角色
    public function actionDelRole($name){
        //echo 11;exit;
        $authManage=\Yii::$app->authManager;
        $permission=$authManage->getRole($name);
        if($permission==null){
            throw new NotFoundHttpException('角色不存在');
        }
        //删除角色
        $authManage->remove($permission);
        //提示操作
        \Yii::$app->session->setFlash('success','删除成功');
        //添加成功跳转到显示的方法
        return $this->redirect(['rbac/index-role']);
    }
//    //设置路由权限
//    public function behaviors(){
//        return[
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//            ]
//        ];
//    }
}








