<?php
/**
 * @var $this \yii\web\View
 */
$form = yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textInput();
echo $form->field($model,'logo')->hiddenInput();
//引入css jss文件资源
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js');
$url=\yii\helpers\Url::to('upload');
$this->registerJs(
        <<<JS
var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    swf: '/js/Uploader.swf',
    // 文件接收服务端。
    server: '{$url}',
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/gif,image/png',
    }
});
//上传成功回显图片
uploader.on( 'uploadSuccess', function( file,response )
 {
     //console.log(response);
     $("#img").attr('src',response);
         //给隐藏狂赋值
     $("#brand-logo").val(response);
});
//回显将图片地址赋值给img标签
var eee=$("#brand-logo").val();
     $("#img").attr('src',eee);
JS
);
?>

    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
    <div><img id="img" style="width: 55px"/></div>
<?php

echo $form->field($model, 'sort')->textInput();
echo $form->field($model, 'status', ['inline' => 1])->radioList([0 => '隐藏', 1 => '展示']);
echo yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
yii\bootstrap\ActiveForm::end();