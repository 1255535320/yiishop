<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'top_menu')->dropDownList(\backend\models\Menu::getAllMenu());
echo $form->field($model,'address')->dropDownList(\backend\models\AuthItem::getItems());
echo $form->field($model,'sort')->textInput();
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-warning']);
yii\bootstrap\ActiveForm::end();