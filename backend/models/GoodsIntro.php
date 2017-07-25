<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord{
    public static function tableName(){
        return 'goods_intro';
    }
    public function rules()
    {
        return [
            ['content', 'string'],
        ];
    }
//    public function getIntro()
//    {
//        return $this->hasMany(Goods::className(),['id'=>'goods_id']);
//    }
}
