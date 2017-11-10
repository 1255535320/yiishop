<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'top_menu')->dropDownList(['=选择上级菜单=','顶级分类',$model->top_menu]);
echo $form->field($model,'address')->dropDownList(['=选择路由=',\backend\models\AuthItem::getItems()]);
echo $form->field($model,'sort')->textInput();
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-warning']);
yii\bootstrap\ActiveForm::end();