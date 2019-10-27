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

    <table id="userTable" lay-filter="user"></table>
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

<!-- 工具栏模板 -->
<script type="text/html" id="toolbarTpl">
    <div class="layui-btn-container">
        <?= Html::a(Yii::t('app', 'Create User'),['create'],['class' =>'layui-btn layui-btn-sm'])?>
        <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="deleteSelected"><?= Yii::t('app', "Delete Selected")?></button>
    </div>
        <form class="layui-form">
            <div class="layui-form-item layui-form-pane" style="margin:10px 0 0 0">
                <div class="layui-inline" style="margin-right:0px">
                    <label class="layui-form-label"><?= Yii::t('app', "UserName")?></label>
                    <div class="layui-input-inline">
                        <input type="text" name="UserSearch[username]" class="layui-input">
                    </div>
                    <label class="layui-form-label"><?= Yii::t('app', "Email")?></label>
                    <div class="layui-input-inline">
                        <input type="text" name="UserSearch[email]" class="layui-input">
                    </div>
                    <div class="layui-table-toolbar-button">
                        <button class="layui-btn" lay-submit lay-filter="userSearch"><i class="layui-icon layui-icon-search"></i></button>
                        <button class="layui-btn layui-btn-primary" type="reset"><i class="layui-icon layui-icon-delete"></i></button>
                    </div>
                </div>
            </div>
        </form>
</script>

<style>
.layui-table-tool-temp{
    padding-right:0px;
}
.layui-table-toolbar-button{
    float:left;
    display:inline-block;
}
</style>

<!-- 右侧操作栏模板 -->
<script type="text/html" id="activebarTpl">
    <div class="layui-btn-group">
    <a class="layui-btn layui-btn-primary layui-btn-xs" href="<?=Url::to(["view", "id"=>""])?>{{d.id}}"><?= Yii::t('app', 'View')?></a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" href="<?=Url::to(["update", "id"=>""])?>{{d.id}}"><?= Yii::t('app', 'Update')?></a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete"><?= Yii::t('app', 'Delete')?></a>
    </div>
</script>

<?php JsBlock::begin();
?>
    layui.use('table',function(){
        var table = layui.table;

        //异步方法渲染表格
        table.render({
            elem: '#userTable'
            ,url: '<?=Url::to(['user/data'])?>'
            //添加csrf验证
            ,where:{ '_csrf-backend': document.getElementsByTagName("meta")['csrf-token']['content']}
            ,request: {
                limitName:'pageSize'
            }
            ,method: "post"
            ,text:{ none:'暂无相关数据'}
            ,page:true
            ,title:"<?=$this->title?>"
            ,toolbar : '#toolbarTpl'
            ,cols: [[
                {checkbox:true}
                ,{type:'numbers',title: '序号'}
                ,{field:'id', title:'ID',hide:true,sort:true}
                ,{field:'username', title:'用户名'}
                ,{field:'email', title:'电子邮箱'}
                ,{field:'status', title:'状态',templet:'#switchTpl', unresize:true,align:'center',sort:true}
                ,{field:'created_at', title:'创建时间'
                    ,templet: '<div>{{layui.util.toDateString(d.created_at*1000)}}</div>'
                    ,sort:true
                }
                ,{field:'updated_at', title:'更新时间'
                    ,templet: '<div>{{layui.util.toDateString(d.updated_at*1000)}}</div>'
                    ,sort:true
                }
                ,{fixed:'right',title: '操作',width:140,align:'left','toolbar':'#activebarTpl'
                }
            ]]
        });

        //监听事件
        table.on('toolbar(user)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            switch(obj.event) {
                case 'getCheckLength':
                    var data = checkStatus.data;
                    layer.msg('选中了：'+ data.length + ' 个');
                break;
                case 'deleteSelected':
                    layer.msg('deleteSelected');
                break;
            };
        });

        table.on('tool(user)', function(obj){
            switch(obj.event) {
                case 'detail':
                    layer.msg('detail');
                break;
                case 'edit':
                    layer.msg('edit');
                break;
                case 'delete':
                    layer.msg('detele');
                break;
                case 'deleteSelected':
                    layer.msg('deleteSelected');
                break;
            };
        });

    });


<?php
JsBlock::end();
?>