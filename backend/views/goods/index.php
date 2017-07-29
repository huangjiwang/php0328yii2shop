<?=\yii\helpers\Html::a('添加',['goods/add'],['class'=>'btn btn-primary'])?>
<?php
$form=\yii\bootstrap\ActiveForm::begin(
    ['layout'=>'inline','method'=>'get','action'=>['goods/index']]
);
echo $form->field($model2,'name')->textInput(['placeholder'=>'商品名称']);
echo $form->field($model2,'sn')->textInput(['placeholder'=>'货号']);
echo \yii\helpers\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>ID</th>
            <th>商品名称</th>
            <th>货号</th>
            <th>图片</th>
            <th>商品分类id</th>
            <th>品牌分类</th>
            <th>市场价格</th>
            <th>商品价格</th>
            <th>库存</th>
            <th>状态</th>
            <th>排序</th>
            <th>添加时间</th>
            <th>浏览次数</th>
            <th>操作</th>
        </tr>
        <?php foreach($model as $row):
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->name?></td>
                <td><?=$row->sn?></td>
                <td><?=\yii\helpers\Html::img($row->logo?Yii::$app->params['imgUrl'].$row->logo:false,['id'=>'logo-img','style'=>'width:100px'])?></td>
                <td><?=$row->category->name?></td>
                <td><?=$row->brand->name?></td>
                <td><?=$row->market_price?></td>
                <td><?=$row->shop_price?></td>
                <td><?=$row->stock?></td>
                <td><?=\backend\models\Goods::$options[$row->status]?></td>
                <td><?=$row->sort?></td>
                <td><?=$row->create_time?></td>
                <td><?=$row->view_times?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$row->id],['class'=>'btn btn-default'])?>
                    <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('查看',['goods/list','id'=>$row->id],['class'=>'btn btn-sm btn-primary'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
                </td>
            </tr>
        <?php endforeach?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);