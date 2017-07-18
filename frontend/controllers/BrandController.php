<?php
namespace frontend\controllers;

use yii\web\Controller;

class BrandController extends Controller{
    //展示列表
    public  function actionindex(){
        $brand=Brand :: find()->all();
        return $this->render()
    }
}