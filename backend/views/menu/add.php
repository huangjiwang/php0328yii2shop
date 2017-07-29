<?php
$form=\yii\bootstrap\ActiveForm::begin();//开启
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->dropDownList($model2,['prompt'=>'=请选择上级菜单=']);
echo $form->field($model,'route')->dropDownList(\backend\models\Menu::getUr10ptions(),['prompt'=>'=请选择路由=']);
echo $form->field($model,'sort');
echo \yii\helpers\Html::submitButton('添加',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();//关闭