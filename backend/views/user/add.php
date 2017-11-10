<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
if ($model->isNewRecord){
    //添加则显示密码框
    echo $form->field($model,'password_hash')->passwordInput();
}
echo $form->field($model,'roles',['inline'=>1])->checkboxList($roles);
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'启用',0=>'禁用']);
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-warning']);
yii\bootstrap\ActiveForm::end();