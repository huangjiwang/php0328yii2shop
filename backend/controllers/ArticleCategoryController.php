<?php
namespace backend\controllers;

use yii\data\Pagination;
use yii\web\Controller;
use backend\models\ArticleCategory;
use yii\web\Request;

class ArticleCategoryController extends Controller
{
    //显示\分页
    public function actionIndex()
    {
        $ArticleCategory = ArticleCategory::find()->where(['or', 'status=1', 'status=0']);
        //获取总条数
        $total = $ArticleCategory->count();
        //每页显示多少条
        $perPage=3;
        //分页工具类
        $pager=new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$perPage
            ]
        );
        $ArticleCategory->orderBy('sort ASC');   // 排序
        $students = $ArticleCategory->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['students'=>$students,'pager'=>$pager]);
    }

    //添加
    public function actionAdd()
    {
        //实例化表单模型
        $model = new ArticleCategory();
        $request = new Request();
        if($request->isPost){
            //加载表单提交的数据
            $model->load($request->post());
            //数据必须要经过验证才能保存
            if($model->validate()){
                $model->save();
                //跳转到列表页
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //调用视图并传值
        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        //var_dump($brand->status);exit;
        return $this->redirect(['article-category/index']);
    }



}