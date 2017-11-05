
<?php
$form=yii\bootstrap\ActiveForm::begin();
echo $form->field($art,'name')->textInput();
echo $form->field($art,'intro')->textarea();
echo $form->field($art,'status',['inline'=>1])->radioList([0=>'隐藏',1=>'正常']);
echo $form->field($art,'article_category_id')->dropDownList(\backend\models\ArticleCategory::getItems());
echo $form->field($art,'sort')->textInput();
echo $form->field($article,'content')->textarea(['rows'=>8]);
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();