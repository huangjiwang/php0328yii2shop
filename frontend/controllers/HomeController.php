<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\GoodsCategory;
use frontend\models\GoodsGallery;
use frontend\models\GoodsIntro;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\web\Controller;
use yii\web\Cookie;
use Yii;
use yii\web\Request;

class HomeController extends Controller{
    public $layout=false;
    public $enableCsrfValidation = false;


    //前台首页
    public function actionIndex(){

        return $this->redirect(['home/goods-category']);

//        if(\Yii::$app->user->isGuest==false){
//            return $this->redirect(['home/goods-category']);
//        }else{
//            return $this->redirect(['member/login']);
//        }


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
                $carts = unserialize($cart->value);
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
    public function actionOrder()
    {
        if (\Yii::$app->user->isGuest) {//未登录
            return $this->redirect(['member/login']);
        } else {
            //收货地址
            $user_name = \Yii::$app->user->identity->username;
            $addresss = Address::find()->where(['=', 'user_name', $user_name])->all();
            //送货方式
            $deliverys = [
                ['name' => '普通快递送货上门', 'freight' => 10, 'intr' => '服务一般,速度较慢'],
                ['name' => '特快快递', 'freight' => 50, 'intr' => '服务一般,速度快'],
                ['name' => '加急快递', 'freight' => 60, 'intr' => '服务一般,速度最快'],
                ['name' => '平邮快递', 'freight' => 10, 'intr' => '服务好,速度慢'],
            ];
            //支付方式
            $payments = [
                ['name' => '货到付款', 'aaa' => '	送货上门后再收款，支持现金、POS机刷卡、支票支付'],
                ['name' => '在线付款', 'aaa' => '	即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
                ['name' => '上门自提', 'aaa' => '	自提时付款，支持现金、POS刷卡、支票支付'],
                ['name' => '邮局汇款', 'aaa' => '	通过快钱平台收款 汇款后1-3个工作日到账'],
            ];

            //商品清单
            $member_id = Yii::$app->user->getId();
            $goods_id = [];
            $carts = [];

            $models = Cart::find()->where(['=', 'member_id', $member_id])->asArray()->all();
            foreach ($models as $model) {
                $goods_id[] = $model['goods_id'];
                $carts[$model['goods_id']] = $model['amount'];
            }
            $goodss = Goods::find()->where(['in', 'id', $goods_id])->asArray()->all();

            $order = new Order();
            $requerst = new Request();
            //判断是否以post方式提交
            if ($requerst->isPost) {
                $order->load($requerst->post(), 'Order');
                //验证数据
                if ($order->validate()) {
                    //用户id
                    $user_name = \Yii::$app->user->id;
                    //收货地址
                    $address = Address::findOne(['consignee' => $order->name]);
                    $order->member_id = $user_name;
                    $order->name = $address->consignee;
                    $order->province = $address->sheng;
                    $order->city = $address->shi;
                    $order->area = $address->xian;
                    $order->address = $address->detailed;
                    $order->tel = $address->tel;
                    //配送方式
                    $delivery = $deliverys[$order->delivery_id];
                    $order->delivery_id = $order->delivery_id;
                    $order->delivery_name = $delivery['name'];
                    $order->delivery_price = $delivery['freight'];
                    //支付方式
                    $payment = $payments[$order->payment_id];
                    $order->payment_id = $order->payment_id;
                    $order->payment_name = $payment['name'];
                    $order->status=1;
                    //创建时间
                    $order->create_time =time();
                    $order->save();
                    //订单商品详情表
                    foreach ($goodss as $goods) {
                        $order_goods = new OrderGoods();
                        $order_goods->order_id = $order->id;
                        $order_goods->goods_id = $goods['id'];
                        $order_goods->goods_name = $goods['name'];
                        $order_goods->logo = $goods['logo'];
                        $order_goods->price = $goods['shop_price'];
                        $order_goods->amount = $carts[$goods['id']];
                        $order_goods->total = $carts[$goods['id']] * $goods['shop_price'];
                        $order_goods->save();
                    }
                    Cart::deleteAll(['member_id' => $user_name]);
                    return $this->render('order2');
                }
            }
            return $this->render('order', ['carts' => $carts, 'goodss' => $goodss, 'addresss' => $addresss, 'deliverys' => $deliverys, 'payments' => $payments]);
        }
    }

    public function actionOrder2(){
        if (\Yii::$app->user->isGuest) {//未登录
            return $this->redirect(['member/login']);
        } else {
            $user_id = \Yii::$app->user->id;
            $models=Order::find()->where(['=','member_id',$user_id])->all();


            return $this->render('order3',['models'=>$models]);
        }
    }
    public function actionOrder3($id){
        $model=Order::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
    }
}