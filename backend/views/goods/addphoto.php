
<?php
$form = yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'path')->hiddenInput();
//web upload方法传图片
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js');
//注册js
$url = yii\helpers\Url::to(['photo','id'=>$_GET['id']]);
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
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpg,image/jpeg,image/png',
    }
});
//上传后展示图片
uploader.on( 'uploadSuccess', function( file,response) {
    // $( '#'+file.id ).addClass('upload-state-done');
    // console.log(file);  //docmount对象信息
    // console.log(response); //图片路径
    $("#img").attr('src',response);
    //把图片地址赋值给logo
    $('#goodsgallery-path').val(response);
 // 修改的时候回显图片
 //    var imgs=$('#goodsgallery-path').val();
    //  $("#img").attr('src',imgs);
});
//修改的时候回显图片
var imgs=$('#goodsgallery-path').val();
// console.debug(99);
 $("#img").attr('src',imgs);
JS
);
?>

<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
    <div><img id="img"/></div>
</div>
<hr>
<?php
//yii\bootstrap\Html::submitButton('提交')
yii\bootstrap\ActiveForm::end();
?>
<table>

    <?php foreach ($models as $modelss):?>
    <tr>
        <td><?=yii\bootstrap\Html::img($modelss->path,['style'=>'width:120px','class'=>'img-circle'])?></td>
        <td><?=yii\bootstrap\Html::a('删除',['/goods/imgdelete','id'=>$modelss->id],['class'=>'btn btn-warning'])?></td>
    </tr>
    <?php endforeach;?>
</table>
