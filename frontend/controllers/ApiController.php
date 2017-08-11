<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller{

    //接口开发必须关闭
    public $enableCsrfValidation = false;
    public function init()
    {
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
    //1.会员
    //-会员注册
    public function actionRegist(){
        if(\Yii::$app->request->isPost){
            $model=new Member();
            $model->username=\Yii::$app->request->post('username');
            $model->password_hash=\Yii::$app->request->post('password_hash');
            $model->code=\Yii::$app->request->post('code');
            $model->repassword=\Yii::$app->request->post('repassword');
            $model->email=\Yii::$app->request->post('email');
            $model->tel=\Yii::$app->request->post('tel');
            //验证规则
            if($model->validate()){
                //验证通过
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);

                //注册成功
                $result=[
                  'errorCode'=>0,
                  'errorMsg'=>'注册成功',
                  'data'=>[],
                ];
            }else{
                //验证失败
                $result=[
                    'errorCode'=>1,
                    'errorMsg'=>'注册失败,请查看错误信息',
                    'data'=>$model->getErrors(),
                    ];
            }
        }else{
            //不是POST 方式请求的
            $result=[
                'errorCode'=>99,
                'errorMsg'=>'请求方式错误,请使用POST提交数据',
                'data'=>[],
            ];
        }
        //返回json格式
        return $result;
    }

    //-会员登录
    public function actionLogin(){
        if(\Yii::$app->request->isPost){
            $model=new LoginForm();
            $model->username=\Yii::$app->request->post('username');
            $model->password=\Yii::$app->request->post('password');
            $model->code=\Yii::$app->request->post('code');
            //验证规则
            if($model->validate()&& $model->login()){
                //验证通过
                //登录成功
                $result=[
                    'errorCode'=>0,
                    'errorMsg'=>'登录成功',
                    'data'=>[],
                ];
            }else{
                //验证失败
                $result=[
                    'errorCode'=>1,
                    'errorMsg'=>'登录失败,请查看错误信息',
                    'data'=>$model->getErrors(),
                ];
            }
        }else{
            //不是POST 方式请求的
            $result=[
                'errorCode'=>99,
                'errorMsg'=>'请求方式错误,请使用POST提交数据',
                'data'=>[],
            ];
        }
        //返回json格式
        return $result;
    }

    //-修改密码
    public function actionUserEdit($id)
    {

    }
    //-获取当前登录的用户信息
    public function actionUserIndex(){
        $user_id = \Yii::$app->user->id;
        $model=Member::findOne(['id'=>$user_id]);
    }

//2.收货地址
//-添加地址
    public function actionAddress(){
        if(\Yii::$app->request->isPost) {
            $model = new Address();
                $model->consignee = \Yii::$app->request->post('consignee');
                $model->detailed = \Yii::$app->request->post('detailed');
                $model->tel = \Yii::$app->request->post('tel');
                $model->sheng = \Yii::$app->request->post('sheng');
                $model->shi = \Yii::$app->request->post('shi');
                $model->xian = \Yii::$app->request->post('xian');
                $model->user_name=\Yii::$app->user->identity->username;
                //验证规则
                if($model->validate()){
                    //验证通过
                    //添加地址成功
                    $model->save(false);
                    $result=[
                        'errorCode'=>0,
                        'errorMsg'=>'添加成功',
                        'data'=>[],
                    ];
                }else{
                    //验证失败
                    $result=[
                    'errorCode'=>1,
                    'errorMsg'=>'添加失败,请查看错误信息',
                    'data'=>$model->getErrors(),
                ];
            }
        }else {
            //不是POST 方式请求的
            $result = [
                'errorCode' => 99,
                'errorMsg' => '请求方式错误,请使用POST提交数据',
                'data' => [],
            ];
        }
        //返回json格式
        return $result;
    }
//-修改地址
    public function actionAddressEdit($id){
        //查询出对应的数据
        $model =Address::findOne(['id'=>$id]);

        if(\Yii::$app->request->post()){
            $model->consignee = \Yii::$app->request->post('consignee');
            $model->detailed = \Yii::$app->request->post('detailed');
            $model->tel = \Yii::$app->request->post('tel');
            $model->sheng = \Yii::$app->request->post('sheng');
            $model->shi = \Yii::$app->request->post('shi');
            $model->xian = \Yii::$app->request->post('xian');
            $model->user_name=\Yii::$app->user->identity->username;
            //验证规则
            if($model->validate()){
                //验证通过
                //添加地址成功
                $model->save(false);
                $result=[
                    'errorCode'=>0,
                    'errorMsg'=>'修改成功',
                    'data'=>[],
                ];
            }else{
                //验证失败
                $result=[
                    'errorCode'=>1,
                    'errorMsg'=>'修改失败,请查看错误信息',
                    'data'=>$model->getErrors(),
                ];
            }
        }else{
            //不是GET 方式请求的
            $result = [
                'errorCode' => 99,
                'errorMsg' => '请求方式错误,请使用POST提交数据',
                'data' => [],
            ];
        }
        return $result;
    }
//-删除地址
//-地址列表

}