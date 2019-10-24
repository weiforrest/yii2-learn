<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\JsBlock;
use yii\grid\GridView;
use backend\models\User;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'layui-btn']) ?>
    <table id="userTable" lay-filter="test"></table>
    <?php /* GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            'status',
            'created_at',
            'updated_at',
            //'verification_token',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */ ?>
    

<!-- 状态切换的模板 -->
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="{{d.status}}" lay-skin="switch" lay-text="Active|InActive" disabled {{ d.status == <?=User::STATUS_ACTIVE?> ? 'checked': ''}}>
</script>

<?php JsBlock::begin();
?>
    layui.use('table',function(){
        var table = layui.table
        ,$ = layui.$
        ,util = layui.util;

        //异步方法渲染表格
        table.render({
            elem: '#userTable'
            ,url: '<?=Url::to(['user/data'])?>'
            ,request: {
                limitName:'pageSize'
            }

            ,page:true
            ,title:"<?=$this->title?>"
            ,cols: [[
                {checkbox:true}
                ,{type:'numbers'}
                //,{field:'id', title:'ID',width:80,hide:true,sort:true}
                ,{field:'username', title:'用户名'}
                ,{field:'email', title:'电子邮箱'}
                ,{field:'status', title:'状态',templet:'#switchTpl', unresize:true,width:100,sort:true}
                ,{field:'created_at', title:'创建时间'
                    ,templet: '<div>{{layui.util.toDateString(d.created_at*1000)}}</div>'
                    ,sort:true
                }
                ,{field:'updated_at', title:'更新时间'
                    ,templet: '<div>{{layui.util.toDateString(d.updated_at*1000)}}</div>'
                    ,sort:true
                }
            ]]
        });
    });


<?php
JsBlock::end();
?>