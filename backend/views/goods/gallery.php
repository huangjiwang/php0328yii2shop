<?php
use flyok666\uploadifive\Uploadifive;
use yii\bootstrap\Html;
use yii\web\JsExpression;
echo Html::fileInput('test', NULL, ['id' => 'test']);
echo  \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['upload/gallery-upload','goods_id'=>$goods_id]),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id'=>$goods_id],//上传文件的同时传参goods_id
        'width' => 80,
        'height' => 30,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data);
        //将上传成功的图片显示到列表里面
        var html='<tr data-id="'+data.id+'" id="gallery_'+data.id+'">';
        html += '<td><img width="100px" src="http://img.yiishop.com:8080'+data.fileUrl+'" /></td>';
        html += '<td><button type="button" class="btn btn-danger del_btn">删除</button></td>';
        html += '</tr>';
        $("table").append(html);
    }
}
EOF
        ),
    ]
]);
?>
    <table class="table">
        <tr>
            <th>图片</th>
            <th>操作</th>
        </tr>
        <?php
        //var_dump($goods_gallery);exit;
        if($goods_gallery){
        foreach($goods_gallery as $gallery):?>
            <tr id="gallery_<?=$gallery->id?>" data-id="<?=$gallery->id?>">
                <td><?=Html::img('http://img.yiishop.com:8080'.$gallery->path,['style'=>'width:100px'])?></td>
                <td><?=Html::button('删除',['class'=>'btn btn-danger del_btn'])?></td>
            </tr>
        <?php endforeach;}?>

    </table>
<?php
$url = \yii\helpers\Url::to(['del-gallery']);
$this->registerJs(new JsExpression(
    <<<EOT
    $("table").on('click',".del_btn",function(){
        if(confirm("确定删除该图片吗?")){
        var id = $(this).closest("tr").attr("data-id");
            $.post("{$url}",{id:id},function(data){
            console.log(data);
                if(data=="success"){
                    //alert("删除成功");
                    $("#gallery_"+id).remove();

                }
            });
        }
    });
EOT
));