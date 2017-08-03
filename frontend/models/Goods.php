<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord{

    public static function tableName(){
        return 'goods';
    }

}