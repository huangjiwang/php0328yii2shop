<?php
$form=\yii\bootstrap\ActiveForm::begin();//开启
echo $form->field($model,'name');
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['upload/s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        //图片地址写入隐藏域
        $('#goods-logo').val(data.fileUrl);
        //图片回显
        $('#logo-img').attr('src','http://img.yiishop.com:8080'+data.fileUrl);
        console.log(data.fileUrl);


    }
}
EOF
        ),
    ]
]);
echo \yii\helpers\Html::img(
    $model->logo?Yii::$app->params['imgUrl'].$model->logo:false,
    ['id'=>'logo-img','style'=>'width:100px']
);
echo $form->field($model,'goods_category_id')->dropDownList($model2);
echo $form->field($model,'brand_id')->dropDownList($model3);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale');
echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::getStatusOptions());
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();//关闭

