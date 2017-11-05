
<table class="table table-hover table-condensed table-bordered">
    <tr class="success">
        <th><h4>编号 </h4></th>
        <th><h4>标题</h4></th>
        <th><h4>文章分类</h4></th>
        <th><h4>操作 </h4></th>
    </tr>
    <?php foreach ($arts as $art): ?>
        <tr>
            <td><?=$art['id'] ?></td>
            <td><?=$art->name ?></td>
            <td><?=$art->articlecategory->name?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['/article/update','id'=>$art->id],['class'=>'btn btn-info'])?>
                <button class="del btn btn-info" href="javascript:;">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a class="btn btn-info" href="<?=yii\helpers\Url::to('/article/add')?>">添加</a><br>
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
