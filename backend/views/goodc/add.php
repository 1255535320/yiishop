<?php
/**
 * @var $this \yii\web\View
 */
$form  = yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
//加载ztree资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//注册js
$nodes=\yii\helpers\Json::encode(\yii\helpers\ArrayHelper::merge([['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],\backend\models\GoodsCategory::getZtree()));

$this->registerJs(
    <<<JS
       var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            callback: {
		onClick: function(event, treeId, treeNode) {
		  //获取选择节点的id
		  var id= treeNode.id;
		  $("#goodscategory-parent_id").val(id);
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
        var zNodes = {$nodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开所有节点
        zTreeObj.expandAll(true);
        //选中节点(回显)
        var node = zTreeObj.getNodeByParam("id",{$model->parent_id}, null);

        //根据id回显节点
        zTreeObj.selectNode(node);
JS
);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js');
echo $form->field($model,'intro')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();
