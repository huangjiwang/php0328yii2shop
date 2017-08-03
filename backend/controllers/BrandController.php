<?php
namespace backend\controllers;
use app\models\Upload;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use backend\filters\RbacFilter;

class BrandController extends Controller{
    //展示列表
    public  function actionIndex(){
        $brand=Brand ::find()->where(['or' , 'status=1' , 'status=0']);
        //获取总条数
        //var_dump($ArticleCategory);exit;
        $total = $brand->count();
        //每页显示多少条
        $perPage=3;
        //分页工具类
        $pager=new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$perPage
            ]
        );
        $brand->orderBy('sort ASC');   // 排序
        $students = $brand->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['brand'=>$students,'pager'=>$pager]);
    }

    //添加
    public function actionAdd(){
        //实列化一个表单
        $brand=new Brand();
        $requerst=new Request();
        //判断是否以post方式提交
        if($requerst->isPost){
            $brand->load($requerst->post());
            //验证数据
            if($brand->validate() &&  $brand->save(false)) {
                return $this->redirect(['brand/index']);
            }else{
                //验证或保存失败
                var_dump($brand->getErrors());exit;
            }
        }
        //调用视图并传值
        return $this->render('add',['brand'=>$brand]);
    }

    //修改
    public function actionEdit($id){
        //实列化一个表单
        $brand=Brand::findOne(['id'=>$id]);
        $requerst=new Request();
        //判断是否以post方式提交
        if($requerst->isPost){
            //验证数据
            if( $brand->load($requerst->post()) && $brand->validate() &&  $brand->save(false)) {
                return $this->redirect(['brand/index']);
            }else{
                //验证或保存失败
                var_dump($brand->getErrors());exit;
            }
        }
        //调用视图并传值
        return $this->render('add',['brand'=>$brand]);
    }

    //删除
    public function actionDelete($id){
        $brand=Brand::findOne(['id'=>$id]);
        $brand->status=-1;
        $brand->save(false);
        //var_dump($brand->status);exit;
        return $this->redirect(['brand/index']);
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