<?php
namespace backend\controllers;


namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\HttpException;

class GoodsCategoryController extends Controller{
    //添加商品分类（ztree选择上级分类id）
    public function actionAdd()
    {
        $model = new GoodsCategory(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
            echo '111';
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //显示
    public function actionIndex()
    {
        $model=GoodsCategory::find()->all();
        return $this->render('index', ['model'=>$model]);
    }

    //修改
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //删除
    public function actionDelete($id){
        $model=GoodsCategory::findOne(['parent_id'=>$id]);
        //var_dump($model);exit;
        if($model){
           \Yii::$app->session->setFlash('danger','有子类不能删除');
            return $this->redirect(['goods-category/index']);
        }else{
            $model=GoodsCategory::findOne(['id'=>$id]);
            $model->delete();
            return $this->redirect(['goods-category/index']);
        }


    }
}