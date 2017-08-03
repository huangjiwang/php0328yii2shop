<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord{

    public static function tableName(){
        return 'goods_intro';
    }
    public function rules()
    {
        return [
            ['amount','safe'],
        ];
    }
}