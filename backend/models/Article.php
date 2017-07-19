<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    public static   $options=[-1=>'删除',0=>'隐藏',1=>'正常'];
    //状态
    public static function getStatusOptions($hidden_del=true)
    {
        if($hidden_del){
            unset(self::$options[-1]);
        }
        return self::$options;
    }
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);//hasOne 返回一个对象
    }
    public function rules(){
        return[
            [['name','intro','article_category_id','sort','status'],'safe'],
            [['name','intro','sort','status'],'required'],
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'article_category_id'=>'文章分类',
            'sort'=>'排序',
            'status'=>'状态',
            'create_time'=>'创建时间',
        ];
    }
}