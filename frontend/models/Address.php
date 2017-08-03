<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Address extends ActiveRecord{
    public function rules()
    {
        return [
            [['consignee','detailed','tel'],'required'],
            [['consignee','sheng','shi','xian','detailed','user_name','tel'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'consignee'=>'收货人',
            'detailed'=>'详细地址',
            'tel'=>'电话',

        ];
    }
}