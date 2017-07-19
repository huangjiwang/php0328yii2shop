<?php
namespace backend\controllers;
use backend\models\Brand;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller{
    //展示列表
    public  function actionIndex(){
        $brand=Brand ::find()->where(['or' , 'status=1' , 'status=0'])->all();
        return $this->render('index',['rows'=>$brand]);
    }

    //添加
    public function actionAdd(){
        //实列化一个表单
        $brand=new Brand();
        $requerst=new Request();
        //判断是否以post方式提交
        if($requerst->isPost){
            $brand->load($requerst->post());
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if($brand->validate()) {
                //文件上传
                if ($brand->imgFile) {
                    $d = \Yii::getAlias('@webroot') . '/upload/' . date('Ymd');
                    if (!is_dir($d)) {
                        mkdir($d,0777,true);
                    }
                    $fileName = '/upload/' . date('Ymd') . '/' . uniqid() . '.' . $brand->imgFile->extension;
                    //创建文件夹
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);

                    $brand->logo= $fileName;
                }
                $brand->save(false);
                return $this->redirect(['brand/index']);
            }else{
                //验证失败 打印错误信息
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
            $brand->load($requerst->post());
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if($brand->validate()) {
                //文件上传
                if ($brand->imgFile) {
                    $d = \Yii::getAlias('@webroot') . '/upload/' . date('Ymd');
                    if (!is_dir($d)) {
                        mkdir($d,0777,true);
                    }
                    $fileName = '/upload/' . date('Ymd') . '/' . uniqid() . '.' . $brand->imgFile->extension;
                    //创建文件夹
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);

                    $brand->logo= $fileName;
                }
                $brand->save(false);
                return $this->redirect(['brand/index']);
            }else{
                //验证失败 打印错误信息
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
}