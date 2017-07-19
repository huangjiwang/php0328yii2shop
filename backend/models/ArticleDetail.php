<?php
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord{
    public function rules(){
        return[
            ['content','safe'],
            ['content','required'],
        ];
    }

    public function attributeLabels(){
        return [
            'content'=>'文章详情',

        ];
    }
    public static function tableName(){
        return 'article_detail';
    }
}