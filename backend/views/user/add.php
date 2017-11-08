<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'email')->textInput();
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-warning']);
yii\bootstrap\ActiveForm::end();