<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 14:33
 */

namespace backend\controllers;


use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\web\Controller;

class UploadController extends Controller
{
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@data/brand',
                'baseUrl' => '@web/brand',//
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $dateFile = date('Ymd',time()).'/';
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$dateFile}{$filename}.{$fileext}";
                },

               /* 'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },*/
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif','bmp'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //图片在自己的服务器上
                    $action->output['fileUrl'] = $action->getWebUrl();
                    //将图片复制一份到七牛云上面
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            'qiniu-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@data/brand',
                'baseUrl' => '@web/brand',//
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $dateFile = date('Ymd',time()).'/';
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$dateFile}{$filename}.{$fileext}";
                },

                /* 'format' => function (UploadAction $action) {
                     $fileext = $action->uploadfile->getExtension();
                     $filehash = sha1(uniqid() . time());
                     $p1 = substr($filehash, 0, 2);
                     $p2 = substr($filehash, 2, 2);
                     return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                 },*/
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif','bmp'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //1.图片在自己的服务器上
                    $action->output['fileUrl'] = $action->getWebUrl();
                    //2.将图片复制一份到七牛云上面
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $key = $action->getWebUrl();
                    $qiniu->uploadFile($action->getSavePath(),$key);
                    $url = $qiniu->getLink($key);
                    $action->output['qiniuUrl'] = $action->getWebUrl();
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];

    }

}