<?php
namespace frontend\controllers;

use yii\web\Controller;

class BrandController extends Controller{
    //չʾ�б�
    public  function actionindex(){
        $brand=Brand :: find()->all();
        return $this->render()
    }
}