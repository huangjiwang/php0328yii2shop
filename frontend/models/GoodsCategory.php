<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord{

    public static function tableName(){
        return 'goods_category';
    }

    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

}