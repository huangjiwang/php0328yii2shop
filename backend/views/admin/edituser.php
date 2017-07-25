<?php
$form=\yii\bootstrap\ActiveForm::begin();//开启
echo $form->field($model,'username');
echo $form->field($model,'jpassword')->passwordInput();
echo $form->field($model,'xpassword')->passwordInput();
echo $form->field($model,'qrpassword')->passwordInput();
echo \yii\helpers\Html::submitButton('添加',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();//关闭