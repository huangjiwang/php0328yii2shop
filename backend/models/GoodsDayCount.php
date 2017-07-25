<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsDayCount extends ActiveRecord{
    public static function tableName(){
        return 'goods_day_count';
    }
    public function rules()
    {
        return [
            [['day', 'count'], 'integer'],
        ];
    }
    public function getCount()
    {
        return $this->hasMany(Goods::className(),['id'=>'id']);
    }
}
