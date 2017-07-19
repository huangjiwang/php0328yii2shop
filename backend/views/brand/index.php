<?=\yii\helpers\Html::a('添加',['brand/add'],['class'=>'btn btn-primary'])?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($rows as $row):
        ?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=\yii\bootstrap\Html::img($row->logo?$row->logo:'/upload/1.jpg',['height'=>50])?></td>
            <td><?=$row->sort?></td>
            <td><?=\backend\models\Brand::$options[$row->status]?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
            </td>
        </tr>
    <?php endforeach?>
</table>