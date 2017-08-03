<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use backend\filters\RbacFilter;

class ArticleController extends Controller{
    //显示
    public function actionIndex(){
        $article=Article :: find()->where(['or', 'status=1', 'status=0']);
        //获取总条数
        $total = $article->count();
        //每页显示多少条
        $perPage=3;
        //分页工具类
        $pager=new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$perPage
            ]
        );
        $article->orderBy('sort ASC');   // 排序
        $students = $article->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['students'=>$students,'pager'=>$pager]);
    }

    //添加
    public function actionAdd(){
    //文章表
    $model=new Article();
    //文章详情表
    $model3=new ArticleDetail();
    $request=new Request();
    //判断是否已POST方式 提交
    if($request->isPost){
        //加载表单提交的数据
        $model->load($request->post());
        $model3->load($request->post());
        //var_dump($model3->load($request->post()));exit;
        //验证
        if($model->validate() && $model3->validate()){
            $model->save();
            //保存之前吧model的ID赋值给model3的ID
            $model3->id=$model->id;
            $model3->save();
            return $this->redirect(['article/index']);
        }else{
            var_dump($model->getErrors());exit;
        }
    }

        //文章分类表
        $model2 =ArticleCategory::find()->where(['or', 'status=1', 'status=0'])->asArray()->all();
        $model2=ArrayHelper::map($model2,'id','name');

        //var_dump($model3);exit;
        //调用视图并传值
        return $this->render('add',['model'=>$model,'model2'=>$model2,'model3'=>$model3]);
    }

    //修改
    public function actionEdit($id){
        //文章表
        $model=Article::findOne(['id'=>$id]);
        //文章详情表
        $model3=ArticleDetail::findOne(['id'=>$id]);
        $request=new Request();
        //判断是否已POST方式 提交
        if($request->isPost){
            //加载表单提交的数据
            $model->load($request->post());
            $model3->load($request->post());
            //var_dump($model3->load($request->post()));exit;
            //验证
            if($model->validate() && $model3->validate()){
                $model->save();
                //保存之前吧model的ID赋值给model3的ID
                $model3->id=$model->id;
                $model3->save();
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }

        //文章分类表
        $model2 =ArticleCategory::find()->where(['or', 'status=1', 'status=0'])->asArray()->all();
        $model2=ArrayHelper::map($model2,'id','name');

        //var_dump($model3);exit;
        //调用视图并传值
        return $this->render('add',['model'=>$model,'model2'=>$model2,'model3'=>$model3]);
    }

    //删除
    public function actionDelete($id){
        $Article=Article::findOne(['id'=>$id]);
        $Article->status=-1;
        $Article->save(false);
        return $this->redirect(['article/index']);
    }

    //查看详情表内容
    public function actionList($id){
        $articledetail=ArticleDetail :: findOne(['id'=>$id]);
        echo '<h1>'.$articledetail->content.'</h1>';
    }

//    //设置路由权限
//    public function behaviors(){
//        return[
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//            ]
//        ];
//    }
}