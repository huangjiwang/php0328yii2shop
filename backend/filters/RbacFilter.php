<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class RbacFilter extends ActionFilter{
    //操作之前
    public function beforeAction($action){
        //判断是否有路由权限
        if(!\Yii::$app->user->can($action->uniqueId)){
            throw new ForbiddenHttpException('您没有该执行权限');
        }
        return parent::beforeAction($action);
    }
}