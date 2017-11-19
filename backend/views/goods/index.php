<?php
$form = yii\bootstrap\ActiveForm::begin(['options'=>['class'=>'form-inline']]);
$form->method='get';
//echo $form->field($see,'sn')->textInput();
echo $form->field($see,'name')->textInput();
//echo $form->field($see,'name')->textInput();
echo yii\bootstrap\Html::submitButton('搜索',['class'=>'glyphicon glyphicon-search']);
yii\bootstrap\ActiveForm::end();
?>
<br>
<table class="table table-hover table-condensed table-bordered">
    <tr class="success">
        <th><h4>编号 </h4></th>
        <th><h4>货号</h4></th>
        <th><h4>商品名</h4></th>
        <th><h4>价格</h4></th>
        <th><h4>库存</h4></th>
        <th><h4>LOGO</h4></th>
        <th><h4>操作 </h4></th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?= $model['id'] ?></td>
            <td><?= $model['sn'] ?></td>
            <td><?= $model['name']?></td>
            <td><?= $model['shop_price'] ?></td>
            <td><?= $model['stock'] ?></td>
            <td><?=yii\bootstrap\Html::img($model->logo,['style'=>'width:50px','class'=>'img-circle'])?></td>
            <td>
                <?=yii\bootstrap\Html::a('相册',['/goods/photo','id'=>$model->id],['class'=>'glyphicon glyphicon-picture'])?>
                <?=yii\bootstrap\Html::a('修改',['/goods/update','id'=>$model->id],['class'=>'btn btn-info'])?>
                <button class="del btn btn-info" href="javascript:;">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a class="btn btn-info" href="<?=yii\helpers\Url::to('/goods/add')?>">添加</a><br>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
?>
<script>
    //1.添加一个点击事件
    $("table").delegate(".del",'click',function () {
        if(confirm('确认删除吗')){
            var id=$(this).closest('tr').find('td:first').text();
//        alert(id)'
            var id2=$(this);
            //传输数据
            $.getJSON('delete',{'id':id},function (data) {
//            //判断
//            alert(data);
                if(data==1) {
                    id2.closest('tr').fadeOut();

                }else{
                    alert('删除失败或该数据不存在!')
                }
            })
        }

    })

</script>
