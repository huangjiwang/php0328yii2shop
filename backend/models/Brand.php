<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    public static   $options=[-1=>'删除',0=>'隐藏',1=>'正常'];
    //状态
    public static function getStatusOptions($hidden_del=true)
    {
        if($hidden_del){
            unset(self::$options[-1]);
        }
        return self::$options;
    }
    public function getBrand()
    {
        return $this->hasMany(Goods::className(),['id'=>'id']);
    }

    public function rules(){
        return[
            [['name','intro','logo','sort','status'],'safe'],
            [['name','intro','sort','status'],'required'],
        ];
    }

    public function attributeLabels(){
        return [
          'name'=>'名称',
          'intro'=>'简介',
          'logo'=>'图片',
          'sort'=>'排序',
          'status'=>'状态',
        ];
    }
}