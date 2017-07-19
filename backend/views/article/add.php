<?php
$form=\yii\bootstrap\ActiveForm::begin();//开启
echo $form->field($model,'name');
echo $form->field($model,'intro');
echo $form->field($model,'article_category_id')->dropDownList($model2);
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::getStatusOptions());
echo $form->field($model3,'content')->textarea();
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();//关闭












