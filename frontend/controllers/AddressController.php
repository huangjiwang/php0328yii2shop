<?php
namespace frontend\controllers;

use frontend\models\Address;
use yii\web\Controller;

class AddressController extends Controller{
    public $layout=false;

    public function actionAddress(){
        $model=new Address();
        //接收参数 验证规则
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
        //判断有没有登录
            if(\Yii::$app->user->isGuest==false){
               $model->user_name=\Yii::$app->user->identity->username;
                $model->save(false);
            }else{
                $this->redirect('http://www.yii2.com:8080/index.php?r=member/login');
            }
        }
        //查询出收货数据 显示表单
        $model2=Address::find()->all();
        return $this->render('address',['model2'=>$model2,'model'=>$model]);
    }

    //修改收货地址
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            if(\Yii::$app->user->isGuest==false){
                $model->user_name=\Yii::$app->user->identity->username;
                $model->save(false);
                $this->redirect(['address/address']);
            }else{
                $this->redirect('http://www.yii2.com:8080/index.php?r=member/login');
            }
        }
        $model2=Address::find()->all();
        return $this->render('address',['model2'=>$model2,'model'=>$model]);
    }

    //删除收货信息
    public function actionDel($id){
        $model=Address::findOne(['id'=>$id]);
        $model->delete();
        $this->redirect(['address/address']);
    }
}