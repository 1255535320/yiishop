
<table class="table table-hover table-condensed table-bordered">
    <tr class="success">
        <th><h4><b>路由</b></h4></th>
        <th><h4><b>描述</b></h4></th>
        <th><h4><b>操作</b></h4></th>
    </tr><b></b>
    <?php foreach ($model as $model): ?>
        <tr>
            <td><?= $model['name'] ?></td>
            <td><?= $model['description']?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['/auth/item_update','name'=>$model->name],['class'=>'btn btn-success'])?>
                <button class="del btn btn-success" href="javascript:;">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a class="btn btn-warning" href="<?=yii\helpers\Url::to('/auth/add_item')?>">添加</a><br>
<script>
    //1.添加一个点击事件
    $("table").delegate(".del",'click',function () {
        if(confirm('确认删除吗')){
            var name=$(this).closest('tr').find('td:first').text();
//        alert(name);
            var name2=$(this);
            //传输数据
            $.getJSON('item_delete',{'name':name},function (data) {
//            //判断
//            alert(data);
                if(data==1) {
                    name2.closest('tr').fadeOut();
                }else{
                    alert('删除失败或该数据不存在!')
                }
            })
        }

    })

</script>
