<?php
namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\data\Pagination;

class GoodsController extends Controller{
    //显示列表
    public function actionIndex()
    {
        //实列化一个模型
        $model=Goods::find()->andwhere('status in(0,1)');
        //获取总条数
        //var_dump($ArticleCategory);exit;
        $total = $model->count();
        //每页显示多少条
        $perPage=3;
        //分页工具类
        $pager=new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$perPage
            ]
        );
        $model2=new GoodsSearchForm();
       $model2->load(\yii::$app->request->get());
        //var_dump($model2);exit;
        if($model2->name){
            $model->andwhere(['like','name',$model2->name]);
        }
        if($model2->sn){
            $model->andwhere(['like','sn',$model2->sn]);
        }

        //跳转到试图
        $model = $model->limit($pager->limit)->offset($pager->offset);
        $model = $model->all();
        return $this->render('index',['model'=>$model,'model2'=>$model2,'pager'=>$pager]);
    }

    //添加
    public function actionAdd(){
        //商品表
        $model=new Goods();
        //文章详情表
        $intro=new GoodsIntro();
        $request=new Request();
        //判断是否以post方式提交的
        if($request->isPost) {
            //接受参数
            $model->load($request->post());
            $intro->load($request->post());
            //var_dump($intro);exit;
            //验证数据
            if ($model->validate() && $intro->validate()) {
                $day=date('Ymd',time());
                $goodsCount=GoodsDayCount::findOne(['day'=>$day]);
                if($goodsCount==null){
                    $goodsCount=new GoodsDayCount();
                    $goodsCount->day=$day;
                    $goodsCount->count=0;
                    $goodsCount->save();
                }
                //字符串长度补全
                $model->sn=date('Ymd').substr('000'.($goodsCount->count+1),-4,4);
                //var_dump($model->sn);exit;
                $model->save();
                $goodsCount->count++;
                $goodsCount->id=$model->id;
                $goodsCount->save(false);
                $model->save(false);
                //文章详情表
                $intro->goods_id=$model->id;
                $intro->save();
                return $this->redirect(['goods/index']);

            } else {
                //验证失败 打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //获取所以分类数据
        //查看商品分类
        $model2 =GoodsCategory::find()->asArray()->all();
        $model2=ArrayHelper::map($model2,'id','name');
        //查看品牌
        $model3 =Brand::find()->where(['or', 'status=1', 'status=0'])->asArray()->all();
        $model3=ArrayHelper::map($model3,'id','name');
        //跳转到试图
        return $this->render('add',['model'=>$model,'model2'=>$model2,'model3'=>$model3,'intro'=>$intro]);

    }

    //修改
    public function actionEdit($id){

        //实列化一个模型
        //商品表
        $model=Goods::findOne(['id'=>$id]);
        //文章详情表
        $intro=GoodsIntro::findOne($id);
        $request=new Request();
        //判断是否以post方式提交的
        if($request->isPost) {
            //接受参数
            $model->load($request->post());

            $intro->load($request->post());
            //var_dump($intro);exit;
            //验证数据
            if ($model->validate() && $intro->validate()) {
                $day=date('Ymd',time());
                $goodsCount=GoodsDayCount::findOne(['day'=>$day]);
                if($goodsCount==null){
                    $goodsCount=new GoodsDayCount();
                    $goodsCount->day=$day;
                    $goodsCount->count=0;
                    $goodsCount->save();
                }
                //字符串长度补全
                $model->sn=date('Ymd').substr('000'.($goodsCount->count+1),-4,4);
                //var_dump($model->sn);exit;
                $model->save();
                $goodsCount->count++;
                $goodsCount->id=$model->id;
                $goodsCount->save(false);

                $model->save(false);
                //文章详情表
                $intro->goods_id=$model->id;
                $intro->save();
                return $this->redirect(['goods/index']);

            } else {
                //验证失败 打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //获取所以分类数据
        //查看商品分类
        $model2 =GoodsCategory::find()->asArray()->all();
        $model2=ArrayHelper::map($model2,'id','name');
        //查看品牌
        $model3 =Brand::find()->where(['or', 'status=1', 'status=0'])->asArray()->all();
        $model3=ArrayHelper::map($model3,'id','name');
        //跳转到试图
        return $this->render('add',['model'=>$model,'model2'=>$model2,'model3'=>$model3,'intro'=>$intro]);

    }

    //删除
    public function actionDelete($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        //var_dump($brand->status);exit;
        return $this->redirect(['goods/index']);
    }
    //查看详情
    public function actionList($id)
    {
        $goodsintro = GoodsIntro:: findOne(['goods_id' => $id]);
        echo '<h1>' . $goodsintro->content . '</h1>';
    }
    /*
    * 商品相册
    */
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        $goods_gallery=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        //var_dump($goods->galleries);exit;
        return $this->render('gallery',['goods_id'=>$id,'goods_gallery'=>$goods_gallery]);
    }

     //AJAX删除图片

    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model  && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }
////设置路由权限
//    public function behaviors(){
//        return[
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//            ]
//        ];
//    }
}