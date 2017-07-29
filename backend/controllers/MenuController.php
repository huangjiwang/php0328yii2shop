<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller{
    //菜单的添加
    public function actionAdd(){
        $model=new Menu();
        $request=new Request();
        //判断是否已POST方式 提交
        if($request->isPost) {
            //加载表单提交的数据
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());exit;
            }

        }
    //跳转到添加页面
        $model2 =Menu::find()->where("parent_id in (0,1)")->asArray()->all();
        $model2=ArrayHelper::map($model2,'id','name');
    return $this->render('add',['model'=>$model,'model2'=>$model2]);
    }
    //菜单的展示
    public function actionIndex(){
       $model=Menu::find()->where("parent_id=1")->all();
        //var_dump($model);exit;

        //跳转到显示页面
        return $this->render('index',['model'=>$model]);
    }
    //菜单的修改
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        $request=new Request();
        //判断是否已POST方式 提交
        if($request->isPost) {
            //加载表单提交的数据
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());exit;
            }

        }
        //跳转到添加页面
        $model2 =Menu::find()->where("parent_id in (0,1)")->asArray()->all();
        $model2=ArrayHelper::map($model2,'id','name');
        return $this->render('add',['model'=>$model,'model2'=>$model2]);
    }
    //菜单的删除
    public function actionDelete($id){
        $model=Menu::findOne(['id'=>$id]);
        //删除用户
        $model->delete();
        //提示操作
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['menu/index']);
    }
}