<?=\yii\helpers\Html::a('添加',['rbac/add-role'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>角色名</th>
            <th>描述</th>
            <th>操作</th>

        </tr>
        <?php foreach($model as $row):
            ?>
            <tr>
                <td><?=$row->name?></td>
                <td><?=$row->description?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$row->name],['class'=>'btn btn-sm btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$row->name],['class'=>'btn btn-sm btn-danger'])?>
                </td>
            </tr>
        <?php endforeach?>
    </table>
<?php