<?php
/**
 * @var $this \yii\web\View
 */
$form = yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
//echo $form->field($model,'sn')->textInput(['readonly'=>'true']);
echo $form->field($model,'goods_category_id')->hiddenInput();
//加载ztree
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//注册js
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js');
$nodes=\yii\helpers\Json::encode(\backend\models\GoodsCategory::getZtree());
//$nodes=\yii\helpers\Json::encode(\backend\models\GoodsCategory::getZtree());

$this->registerJs(
        <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
           callback: {
		onClick: function(event, treeId, treeNode) {
		  //获取选择节点的id
		  var id= treeNode.id;
		  $("#goods-goods_category_id").val(id);
		}
	},
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes =$nodes;
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开分类列表
            zTreeObj.expandAll(true);
            //选中节点(回显)---先获取
                //1.获取
                var node = zTreeObj.getNodeByParam("id", {$model->goods_category_id}, null);
                //2.选中节点
            zTreeObj.selectNode(node);

       
JS

);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getItems());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'logo')->hiddenInput();
//web upload方法传图片
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js');
//注册js
$url = yii\helpers\Url::to(['ajax']);
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
    // console.log(file);  docmount对象信息
    // console.log(response); 图片路径
    $("#img").attr('src',response);
    //把图片地址赋值给logo
    $('#goods-logo').val(response);
 //修改的时候回显图片
    var imgs=$('#goods-logo').val();
    console.debug(99);
     $("#img").attr('src',imgs);
});
//修改的时候回显图片
var imgs=$('#goods-logo').val();
// console.debug(99);
 $("#img").attr('src',imgs);
JS
)
?>
<div id="uploader-demo">
     <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
    <div><img id="img"/></div>
</div>
<?php
//echo $form->field($intro,'content')->textarea(['rows'=>8]);
echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[]);
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-warning']);
yii\bootstrap\ActiveForm::end();