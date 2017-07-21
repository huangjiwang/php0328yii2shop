<?php
namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class GoodsCategory extends ActiveRecord{
    public function rules(){
        return [
            [['tree','lft','rgt','depth','parent_id'],'integer'],
            [['intro'],'string'],
            [['name'],'string','max'=>50],
            [['name','parent_id'],'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'名称',
            'parent_id'=>'上级分类id',
            'intro'=>'简介',
        ];
    }

    public static function tableName(){
        return 'goods_category';
    }
//嵌套集合行为
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                 'leftAttribute' => 'lft',
                 'rightAttribute' => 'rgt',
                 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
}