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

    <?php //放到table外 避免每次重载table时 也会刷新form?>
    <form class="layui-form">
        <div class="layui-form-item layui-form-pane" style="margin:10px 0 0 0">
            <div class="layui-inline" style="margin-right:0px">
                <label class="layui-form-label"><?= Yii::t('app', "UserName")?></label>
                <div class="layui-input-inline">
                    <input type="text" name="username" id="username" class="layui-input">
                </div>
                <label class="layui-form-label"><?= Yii::t('app', "Email")?></label>
                <div class="layui-input-inline">
                    <input type="text" name="email" id="email" class="layui-input">
                </div>
                <div class="layui-table-toolbar-button">
                    <button class="layui-btn" lay-submit lay-filter="userSearch"><i class="layui-icon layui-icon-search"></i></button>
                    <button class="layui-btn layui-btn-primary" type="reset"><i class="layui-icon layui-icon-delete"></i></button>
                </div>
            </div>
        </div>
    </form>
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

<!-- 头部工具栏模板 -->
<script type="text/html" id="toolbarTpl">
    <div class="layui-btn-container">
        <?= Html::a(Yii::t('app', 'Create User'),['create'],['class' =>'layui-btn layui-btn-sm'])?>
        <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="deleteSelected"><?= Yii::t('app', "Delete Selected")?></button>
    </div>
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
    layui.use(['table','form'],function(){
        var table = layui.table
        ,$ = layui.$
        ,form = layui.form
        ,csrfToken = document.getElementsByTagName("meta")['csrf-token']['content'];

        //异步方法渲染表格
        var userTable = table.render({
            elem: '#userTable'
            ,url: '<?=Url::to(['user/data'])?>'
            //添加csrf验证
            ,where: {"_csrf-backend":csrfToken}
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

        //监听头部工具栏事件
        table.on('toolbar(user)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            var username = document.getElementById("username").value;
            var email = document.getElementById("email").value;
            switch(obj.event) {
                case 'getCheckLength':
                    var data = checkStatus.data;
                    layer.msg('选中了：'+ data.length + ' 个');
                break;
                case 'deleteSelected':
                    layer.msg('deleteSelected');
                break;
                /*case 'searchSubmit':
                    username = 'test';
                    email = 'test';
                    layer.msg('formSubmit');
                break;
                case 'resetSearch':
                    username = '';
                    email = '';
                    layer.msg('resetSearch');
                break;*/
            };
        });

        //监听行工具栏事件
        table.on('tool(user)', function(obj){
            switch(obj.event) {
                case 'delete':
                    $.post(
                        "<?= Url::to(['user/delete'])?>"
                        ,{id:obj.data.id, "_csrf-backend":csrfToken}
                        ,function (data) {
                            if(data.code == 0){
                                obj.del();
                                layer.msg(data.msg);
                            }
                        }
                    );
                    /*$.ajax({
                        type:'post'
                        ,url:"<?= Url::to(['user/delete'])?>"
                        ,data:{"_csrf-backend":csrfToken, id:obj.data.id} 
                        ,success:function(data) {
                            if(data.code == 0){
                                obj.del();
                                layer.msg(data.msg);
                            }
                        }
                    });*/
                break;
            };
        });

        // 监听搜索框
        form.on('submit(userSearch)', function(data){
            console.log(data.field.username);
            //console.log(data.field.email);
            //根据搜索条件重载表格
            userTable.reload({
                where:{
                    "_csrf-backend":csrfToken
                    ,"username":data.field.username
                    ,"email":data.field.email
                }
            })
            layer.msg("search");
            return false;
        });

    });


<?php
JsBlock::end();
?>