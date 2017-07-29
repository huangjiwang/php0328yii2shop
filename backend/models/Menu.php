<?php

namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord
{

    public function rules()
    {
        return [
            [['name', 'route', 'parent_id', 'sort'], 'safe'],
            [['name', 'parent_id', 'sort'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'route' => '地址(路由)',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }
//获取地址
    public static function getUr10ptions(){
        return ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','name');
    }

//获取子菜单
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}












