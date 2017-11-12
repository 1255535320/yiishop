
<table class="table table-hover table-condensed table-bordered">
    <tr class="success">
        <th><h4><b>ID</b></h4></th>
        <th><h4><b>用户名</b></h4></th>
        <th><h4><b>邮箱</b></h4></th>
        <th><h4><b>状态</b></h4></th>
        <th><h4><b>操作</b></h4></th>
    </tr><b></b>
    <?php foreach ($model as $model): ?>
        <tr>
            <td><?= $model['id']?></td>
            <td><?= $model['username']?></td>
            <td><?= $model['email']?></td>
            <td><?=$model->status==1?'启用':'禁用'?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['/user/update','id'=>$model->id],['class'=>'btn btn-success'])?>
                <button class="del btn btn-success" href="javascript:;">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a class="btn btn-warning" href="<?=yii\helpers\Url::to('/user/add')?>">添加</a><br>
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
