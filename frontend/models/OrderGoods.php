<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord{

    public function rules()
    {
        return [
            [['order_id','goods_id','goods_name','logo','price','amount','total'],'safe'],
        ];
    }
    public static function tableName(){
        return 'order_goods';
    }
    public function getOrder()
    {
        return $this->hasMany(Order::className(),['id'=>'order_id']);//hasOne 返回一个对象
    }

}