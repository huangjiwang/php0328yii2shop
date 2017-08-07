<?=\yii\helpers\Html::a('添加',['menu/add'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>名称</th>
            <th>地址(路由)</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        <?php foreach($model as $rows):?>
            <tr>
                <td><?=$rows->name?></td>
                <td><?=$rows->route?></td>
                <td><?=$rows->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$rows->id],['class'=>'btn btn-sm btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$rows->id],['class'=>'btn btn-sm btn-danger'])?>
                </td>
            </tr>
            <?php foreach($rows->children as $row):
                ?>
                <tr>
                    <td>----<?=$row->name?></td>
                    <td><?=$row->route?></td>
                    <td><?=$row->sort?></td>
                    <td>
                        <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                        <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
                    </td>
                </tr>
            <?php endforeach?>
        <?php endforeach?>
    </table>