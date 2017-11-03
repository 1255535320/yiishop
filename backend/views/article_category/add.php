<?php
$form =yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList([0=>'隐藏',1=>'展示']);
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();