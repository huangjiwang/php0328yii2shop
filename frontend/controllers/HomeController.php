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


    //ǰ̨��ҳ
    public function actionIndex(){

        if(\Yii::$app->user->isGuest==false){
            return $this->redirect(['home/goods-category']);
        }else{
            return $this->redirect(['member/login']);
        }


    }
    //��Ʒ��������б�
    public function actionGoodsCategory(){
        $goodscategory=GoodsCategory::find()->where(['parent_id'=>0])->all();
        //var_dump($goodscategory);exit;
        return $this->render('index',['goodscategory'=>$goodscategory]);
    }

    //��Ʒ�б�
    public function actionGoods($id){
        //����һ��ģ�� ��ѯ�����Ʒ��������Ʒ��Ӧ����Ϣ
    $model=Goods::find()->where(['goods_category_id'=>$id])->all();
        //var_dump($model);exit;
        return $this->render('list',['model'=>$model]);
    }

    //��Ʒ����ҳ
    public function actionGoodsIntro($id){
        //��ѯ����Ʒ������Ϣ
       $model=GoodsIntro::findOne(['goods_id'=>$id]);
        //��ѯ����Ʒ��Ϣ
        $model2=Goods::findOne(['id'=>$id]);
        //��ѯ����Ʒ�����Ϣ
        $model3=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //���ղ�������֤
//        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
//            var_dump($model);exit;
//        }

        return $this->render('goods',['model'=>$model,'model2'=>$model2,'model3'=>$model3]);
    }

    //��ӵ����ﳵ�ɹ�ҳ��
    public function actionAddToCart($goods_id=0,$amount=0)
    {
        //δ��¼
        if(Yii::$app->user->isGuest){
            //���û�е�¼�ʹ����cookie��
            $cookies = Yii::$app->request->cookies;
            //��ȡcookie�еĹ��ﳵ����
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);
                if(isset($carts[$goods_id])){
                    //���ﳵ���Ѿ��и���Ʒ�������ۼ�
                    $carts[$goods_id] += $amount;
                }else{
                    //���ﳵ��û�и���Ʒ
                    $carts[$goods_id] = $amount;
                }
            }
            //����Ʒid����Ʒ����д��cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
        }else{
            //�û��ѵ�¼���������ﳵ���ݱ�
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
    //���ﳵҳ��
    public function actionCart()
    {
        //1 �û�δ��¼�����ﳵ���ݴ�cookieȡ��
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cart->value);
            }
            //��ȡ��Ʒ����
            $models = Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
        }else{
            //2 �û��ѵ�¼�����ﳵ���ݴ����ݱ�ȡ
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
    //�޸Ĺ��ﳵ����
    public function actionAjaxCart()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        //������֤
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            //��ȡcookie�еĹ��ﳵ����
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99��2=��1]
                if(isset($carts[$goods_id])){
                    //���ﳵ���Ѿ��и���Ʒ����������
                    $carts[$goods_id] = $amount;
                }else{
                    //���ﳵ��û�и���Ʒ
                    $carts[$goods_id] = $amount;
                }
            }
            //����Ʒid����Ʒ����д��cookie
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
    //ɾ��δ��¼�Ĺ��ﳵ����
    public function actionDelCart($goods_id)
    {
        if (\Yii::$app->user->isGuest) {//δ��¼
            $cookies = \Yii::$app->request->cookies;
            if ($cookies->get('cart') != null) {
                $cookie = $cookies->get('cart');
                $carts = unserialize($cookie->value);
                if (isset($carts[$goods_id])) {
                    //���ﳵ���Ѿ��и���Ʒ����������
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
            //��¼
            $member_id = \Yii::$app->user->getId();
            Cart::deleteAll(['goods_id' => $goods_id, 'member_id' => $member_id]);
        }
        return $this->redirect(['cart']);

    }

    //������
    public function actionOrder(){
        
        return $this->render('order');
    }

}