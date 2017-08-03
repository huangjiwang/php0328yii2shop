<?php
namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\GoodsCategory;
use frontend\models\GoodsGallery;
use frontend\models\GoodsIntro;
use yii\web\Controller;
use yii\web\Cookie;
use Yii;

class HomeController extends Controller{
    public $layout=false;
    public $enableCsrfValidation = false;


    //前台首页
    public function actionIndex(){

        if(\Yii::$app->user->isGuest==false){
            return $this->redirect(['home/goods-category']);
        }else{
            return $this->redirect(['member/login']);
        }


    }
    //商品分类管理列表
    public function actionGoodsCategory(){
        $goodscategory=GoodsCategory::find()->where(['parent_id'=>0])->all();
        //var_dump($goodscategory);exit;
        return $this->render('index',['goodscategory'=>$goodscategory]);
    }

    //商品列表
    public function actionGoods($id){
        //创建一个模型 查询查出商品分类与商品对应的信息
    $model=Goods::find()->where(['goods_category_id'=>$id])->all();
        //var_dump($model);exit;
        return $this->render('list',['model'=>$model]);
    }

    //商品详情页
    public function actionGoodsIntro($id){
        //查询出商品详情信息
       $model=GoodsIntro::findOne(['goods_id'=>$id]);
        //查询出商品信息
        $model2=Goods::findOne(['id'=>$id]);
        //查询出商品相册信息
        $model3=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //接收参数并验证
//        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
//            var_dump($model);exit;
//        }

        return $this->render('goods',['model'=>$model,'model2'=>$model2,'model3'=>$model3]);
    }

    //添加到购物车成功页面
    public function actionAddToCart($goods_id=0,$amount=0)
    {
        //未登录
        if(Yii::$app->user->isGuest){
            //如果没有登录就存放在cookie中
            $cookies = Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，数量累加
                    $carts[$goods_id] += $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
        }else{
            //用户已登录，操作购物车数据表
            $model=new Cart();
            if($model->validate()){
                $model->goods_id=$goods_id;
                $model->amount=$amount;
                $model->member_id=\Yii::$app->user->id;
                $model->save();
            }

        }


        return $this->redirect(['cart']);
    }
    //购物车页面
    public function actionCart()
    {
        //1 用户未登录，购物车数据从cookie取出
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cart->value);
            }
            //获取商品数据
            $models = Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
        }else{
            //2 用户已登录，购物车数据从数据表取
            $member_id=Yii::$app->user->getId();
            $goods_id=[];
            $carts=[];

            $models = Cart::find()->where(['=','member_id',$member_id])->asArray()->all();
            foreach($models as $model){
                $goods_id[]=$model['goods_id'];
                $carts[$model['goods_id']]=$model['amount'];

            }
            $models= Goods::find()->where(['in','id',$goods_id])->asArray()->all();
        }
        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }
    //修改购物车数据
    public function actionAjaxCart()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        //数据验证
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }
    }
    //删除未登录的购物车数据
    public function actionDelCart($goods_id)
    {
        if (\Yii::$app->user->isGuest) {//未登录
            $cookies = \Yii::$app->request->cookies;
            if ($cookies->get('cart') != null) {
                $cookie = $cookies->get('cart');
                $carts = unserialize($cookie->value);
                if (isset($carts[$goods_id])) {
                    //购物车中已经有该商品，更新数量
                    unset($carts[$goods_id]);
                    $cookie = new Cookie([
                        'name' => 'cart',
                        'value' => serialize($carts),
                        'expire' => 7 * 24 * 3600 + time(),
                    ]);
                    $cookies = \Yii::$app->response->cookies;
                    $cookies->add($cookie);
                }
            }
        } else {
            //登录
            $member_id = \Yii::$app->user->getId();
            Cart::deleteAll(['goods_id' => $goods_id, 'member_id' => $member_id]);
        }
        return $this->redirect(['cart']);

    }

    //订单表
    public function actionOrder(){
        
        return $this->render('order');
    }

}