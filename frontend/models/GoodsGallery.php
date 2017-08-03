<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord{

    public static function tableName(){
        return 'goods_gallery';
    }

}