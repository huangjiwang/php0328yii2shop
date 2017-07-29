<?php
namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    const SCENARIO_ADD='add';
    public $name;//权限名称
    public $description;//权限描述

    public function rules(){
        return[
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }

    public function attributeLabels(){
        return[
            'name'=>'名称(路由)',
            'description'=>'描述'
        ];
    }

    public function validateName(){
        $authManage=\Yii::$app->authManager;
        if($authManage->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
}