<?php
namespace backend\models;

use common\models\Article;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    //状态
    public static   $options=[-1=>'删除',0=>'隐藏',1=>'正常'];
    public static function getStatusOptions($hidden_del=true)
    {
        if($hidden_del){
            unset(self::$options[-1]);
        }
        return self::$options;
    }
    public function rules(){
        return[
           [['name','logo','market_price','shop_price','goods_category_id','brand_id','stock','is_on_sale','status','sort'], 'string'],
           [['name','status','sort','market_price','shop_price','stock','is_on_sale'], 'required'],
        ];
    }
    public function getBrand()
    {
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);//hasOne 返回一个对象
    }
    public function getCategory()
    {
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);//hasOne 返回一个对象
    }
    public function getGoodsDayCount()
    {
        return $this->hasOne(GoodsCategory::className(),['id'=>'sn']);//hasOne 返回一个对象
    }
//    public function getGoodsIntro()
//    {
//        return $this->hasOne(GoodsIntro::className(),['id'=>'goods_id']);//hasOne 返回一个对象
//    }
    //搜索
    public function search($params)
    {
        $query = Article::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2,
            ],
        ]);
        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // 增加过滤条件来调整查询对象
        $query->andFilterWhere([
            'title' => $this->title,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }

    public function attributeLabels(){
        return [
            'name'=>'商品名称',
            'sn'=>'货号',
            'logo'=>'图片',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'status'=>'状态',
            'sort'=>'排序',
        ];
    }
}