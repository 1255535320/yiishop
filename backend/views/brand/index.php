
<table class="table table-hover table-condensed table-bordered">
    <tr class="success">
        <th><h4>编号 </h4></th>
        <th><h4>品牌名</h4></th>
        <th><h4>品牌logo</h4></th>
        <th><h4>操作 </h4></th>
    </tr>
    <?php foreach ($model as $model): ?>
        <tr>
            <td><?= $model['id'] ?></td>
            <td><?= $model['name'] ?></td>
            <td><?=yii\bootstrap\Html::img($model->logo,['style'=>'width:50px','class'=>'img-circle'])?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['/brand/update','id'=>$model->id],['class'=>'btn btn-info'])?>
                <button class="del btn btn-info" href="javascript:;">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a class="btn btn-info" href="<?=yii\helpers\Url::to('/brand/add')?>">添加</a><br>
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
