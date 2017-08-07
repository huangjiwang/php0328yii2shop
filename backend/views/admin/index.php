<?php
    if(\Yii::$app->user->isGuest==false){
       echo  '登录用户名:'.\Yii::$app->user->identity->username.'&nbsp;&nbsp;';
       echo \yii\helpers\Html::a('修改个人信息',['admin/edituser','id'=>\Yii::$app->user->identity->id],['class'=>'btn btn-primary btn-xs']);
    }else{
        echo '登录用户名:游客';
    }
?>

<br/>
<br/>
<?=\yii\helpers\Html::a('添加',['admin/add'],['class'=>'btn btn-primary'])?>
<?=\yii\helpers\Html::a('注销',['admin/logout'],['class'=>'btn btn-danger'])?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>最后登录时间</th>
            <th>最后登录IP</th>
            <th>操作</th>
        </tr>
        <?php foreach($model as $row):
            //var_dump($model->username);exit;
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->username?></td>
                <td><?=$row->last_login_time?></td>
                <td><?=$row->last_login_ip?></td>

                <td>
                    <?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$row->id],['class'=>'btn btn-sm btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['admin/delete','id'=>$row->id],['class'=>'btn btn-sm btn-danger'])?>
                </td>
            </tr>
        <?php endforeach?>
    </table>