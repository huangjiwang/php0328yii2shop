<?php
$form=\yii\bootstrap\ActiveForm::begin();//开启
echo $form->field($brand,'name');
echo $form->field($brand,'intro');
if(isset($brand->logo)){echo \yii\bootstrap\Html::img($brand->logo,['height'=>50]);}
echo $form->field($brand,'imgFile')->fileInput();
echo $form->field($brand,'sort')->textInput(['type'=>'number']);
echo $form->field($brand,'status',['inline'=>true])->radioList(\backend\models\Brand::getStatusOptions());
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();//关闭












