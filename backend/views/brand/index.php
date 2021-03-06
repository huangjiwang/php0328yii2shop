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
    <?php foreach($brand as $row):
        ?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
           <td><?=\yii\helpers\Html::img($row->logo?Yii::$app->params['imgUrl'].$row->logo:false,['id'=>'logo-img','style'=>'width:100px'])?></td>
            <td><?=$row->sort?></td>
            <td><?=\backend\models\Brand::$options[$row->status]?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
            </td>
        </tr>
    <?php endforeach?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);