<h1>菜单管理表</h1><br>
<table class="table table-hover table-condensed">
    <tr>
        <th><h4><b>菜单名</b></h4></th>
        <th><h4><b>路由</b></h4></th>
        <th><h4><b>排序</b></h4></th>
        <th><h4><b>操作</b></h4></th>
    </tr>
    <?php foreach ($model as $model): ?>
        <tr  id=<?=$model['id'] ?>>
            <td><?=str_repeat("====",$model->depth).$model['name'] ?></td>
            <td><?=$model['address'] ?></td>
            <td><?=$model['sort'] ?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['/menu/menu_update','id'=>$model->id],['class'=>'btn btn-info'])?>
                <button class="del btn btn-info"  href="javascript:;">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a class="btn btn-info" href="<?=yii\helpers\Url::to('/menu/menu_add')?>">添加</a><br>
<script>
    //1.添加一个点击事件
    $("table").delegate(".del",'click',function () {
        if(confirm('确认删除吗')){
            var id=$(this).closest('tr').attr('id');
//        alert(id);
            var id2=$(this);
            //传输数据
            $.getJSON('menu_delete',{'id':id},function (data) {
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
