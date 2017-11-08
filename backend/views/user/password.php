<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($password,'oldpassword')->passwordInput();
echo $form->field($password,'newpassword')->passwordInput();
echo $form->field($password,'repassword')->passwordInput();
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-warning']);
yii\bootstrap\ActiveForm::end();