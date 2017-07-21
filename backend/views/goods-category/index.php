<?=\yii\helpers\Html::a('添加',['goods-category/add'],['class'=>'btn btn-primary'])?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>层级</th>
        <th>名称</th>
        <th>上级分类ID</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $row):
        ?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->depth?></td>
            <td><?=$row->name?></td>
            <td><?=$row->parent_id?></td>
            <td><?=$row->intro?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
            </td>
        </tr>
    <?php endforeach?>
</table>