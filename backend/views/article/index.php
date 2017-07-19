<?=\yii\helpers\Html::a('添加',['article/add'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>简介</th>
            <th>文章分类ID</th>
            <th>排序</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach($students as $row):
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->name?></td>
                <td><?=$row->intro?></td>
                <td><?=$row->category->name?></td>
                <td><?=$row->sort?></td>
                <td><?=\backend\models\Brand::$options[$row->status]?></td>
                <td><?=$row->create_time?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('查看',['article/list','id'=>$row->id],['class'=>'btn btn-sm btn-info'])?>
                    <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
                </td>
            </tr>
        <?php endforeach?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);