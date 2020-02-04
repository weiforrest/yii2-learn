<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\JsBlock;
use yii\grid\GridView;
use backend\models\Admin;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Admins');
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php //搜索框放到table外 避免每次重载table时 也会刷新form?>
    <form class="layui-form">
        <div class="layui-form-item layui-form-pane" style="margin:10px 0 0 0">
            <div class="layui-inline" style="margin-right:0px">
                <label class="layui-form-label"><?= Yii::t('app', "UserName")?></label>
                <div class="layui-input-inline">
                    <input type="text" name="username" id="username" class="layui-input">
                </div>
                <label class="layui-form-label"><?= Yii::t('app', "Nickname")?></label>
                <div class="layui-input-inline">
                    <input type="text" name="nickname" id="nickname" class="layui-input">
                </div>
                <div class="layui-table-toolbar-button">
                    <button class="layui-btn" lay-submit lay-filter="Search"><i class="layui-icon layui-icon-search"></i></button>
                    <button class="layui-btn layui-btn-primary" type="reset"><i class="layui-icon layui-icon-delete"></i></button>
                </div>
            </div>
        </div>
    </form>
    <table id="indexTable" lay-filter="index"></table>
    

<!-- 状态切换的模板 -->
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-filter="status" lay-text="Active|InActive"  {{ d.status == <?=Admin::STATUS_ACTIVE?> ? 'checked': ''}}>
</script>

<!-- 头部工具栏模板 -->
<script type="text/html" id="toolbarTpl">
    <div class="layui-btn-container">
        <?= Html::a(Yii::t('app', 'Create Admin'),['create'],['class' =>'layui-btn layui-btn-sm'])?>
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
        //,csrfToken = document.getElementsByTagName("meta")['csrf-token']['content'];
        , csrfToken = "<?=Yii::$app->request->getCsrfToken()?>"; 

        //异步方法渲染表格
        var indexTable = table.render({
            elem: '#indexTable'
            ,url: '<?=Url::to(['admin/data'])?>'
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
                ,{field:'nickname', title:'昵称'}
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
        table.on('toolbar(index)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            var data = checkStatus.data;
            switch(obj.event) {
                case 'getCheckLength':
                    layer.msg('选中了：'+ data.length + ' 个');
                break;
                case 'deleteSelected':
                    if(data.length != 0) {
                        layer.confirm("确定删除共"+data.length+"个用户?", function(index){
                            console.log(data);
                            /*
                            //构建[{id:1},{id:2}] 数组
                            var result = data.map(({id}) => {
                                return {id};
                            });*/
                            var result = data.map(function(val,index){
                                return val.id;
                            });
                            //console.log({ id:result, "_csrf-backend":csrfToken});

                            $.post(
                                "<?=Url::to(['admin/delete'])?>"
                                ,{ id:result, "_csrf-backend":csrfToken}
                                ,function(data){
                                    if(data.code == 0){
                                        //删除所有行
                                        layer.msg(data.msg);
                                    } else{
                                        layer.msg(data.msg);
                                    }
                                }
                            );
                            layer.close(index);
                           // TODO:
                           // 添加自动刷新当前页面的功能 
                        });
                    }
                break;
            };
        });

        //监听行工具栏事件
        table.on('tool(index)', function(obj){
            switch(obj.event) {
                case 'delete':
                    layer.confirm('确定删除 '+obj.data.nickname+' 管理员?', function(index){
                        $.post(
                            "<?= Url::to(['admin/delete'])?>"
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
                            ,url:"<?= Url::to(['admin/delete'])?>"
                            ,data:{"_csrf-backend":csrfToken, id:obj.data.id} 
                            ,success:function(data) {
                                if(data.code == 0){
                                    obj.del();
                                    layer.msg(data.msg);
                                }
                            }
                        });*/

                        // 关闭窗口
                        layer.close(index);
                        // TODO:
                        // 添加自动刷新当前页面的功能 

                    });
                break;
            };
        });

        // 监听switch按钮
        form.on('switch(status)',function(data){
            var id = data.value;
            var status = data.elem.checked ? "<?=Admin::STATUS_ACTIVE?>":"<?=Admin::STATUS_INACTIVE?>";
            $.post(
                "<?= Url::to(['admin/status'])?>"
                ,{id:id, status:status,"_csrf-backend":csrfToken}
                ,function (data) {
                    if(data.code == 0){
                        layer.msg(data.msg);
                    }else{
                        layer.msg('返回码'+data.code+' '+data.msg);
                    }
                }
            );

        });

        // 监听搜索框
        form.on('submit(Search)', function(data){
            console.log(data.field.username);
            //console.log(data.field.email);
            //根据搜索条件重载表格
            indexTable.reload({
                where:{
                    "_csrf-backend":csrfToken
                    ,"username":data.field.username
                    ,"nickname":data.field.nickname
                }
            })
            layer.msg("search");
            return false;
        });

    });


<?php
JsBlock::end();
?>