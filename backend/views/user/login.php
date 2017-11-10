<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'remember')->checkbox([1=>'记住登陆']);
echo yii\bootstrap\Html::submitButton('登陆',['class'=>'btn btn-warning']);
//echo yii\bootstrap\Html::resetButton('重置',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();