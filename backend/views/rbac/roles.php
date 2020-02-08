<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\JsBlock;

$this->title = Yii::t('app','Roles');
$this->params['breadcrumbs'][] = $this->title;

?>

    <?php //搜索框放到table外 避免每次重载table时 也会刷新form?>
    <form class="layui-form">
        <div class="layui-form-item layui-form-pane" style="margin:10px 0 0 0">
            <div class="layui-inline" style="margin-right:0px">
                <label class="layui-form-label"><?= Yii::t('app', "Name")?></label>
                <div class="layui-input-inline">
                    <input type="text" name="name" id="name" class="layui-input">
                </div>
                <div class="layui-table-toolbar-button">
                    <button class="layui-btn" lay-submit lay-filter="search"><i class="layui-icon layui-icon-search"></i></button>
                    <button class="layui-btn layui-btn-primary" type="reset"><i class="layui-icon layui-icon-delete"></i></button>
                </div>
            </div>
        </div>
    </form>
    <table id="indexTable" lay-filter="roles"></table>
    

<!-- 头部工具栏模板 -->
<script type="text/html" id="toolbarTpl">
    <div class="layui-btn-container">
        <?= Html::a(Yii::t('app', 'Create Role'),['createrole'],['class' =>'layui-btn layui-btn-sm'])?>
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
    <a class="layui-btn layui-btn-primary layui-btn-xs" href="<?=Url::to(["role", "name"=>""])?>{{d.name}}"><?= Yii::t('app', 'View')?></a>
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

        //直接渲染表格
        var indexTable = table.render({
            elem: '#indexTable'
            ,url: '<?=Url::to(['roles'])?>'
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
                ,{field:'name', title:'名称'}
                ,{field:'description', title:'描述'}
                //,{field:'rule', title:'规则'}
                //,{field:'data', title:'数据'}
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
        table.on('toolbar(roles)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            var data = checkStatus.data;
            switch(obj.event) {
                case 'getCheckLength':
                    layer.msg('选中了：'+ data.length + ' 个');
                break;
                case 'deleteSelected':
                    if(data.length != 0) {
                        layer.confirm("确定删除共"+data.length+"个角色?", function(index){
                            console.log(data);
                            /*
                            //构建[{id:1},{id:2}] 数组
                            var result = data.map(({id}) => {
                                return {id};
                            });*/
                            var result = data.map(function(val,index){
                                return val.name;
                            });
                            //console.log({ id:result, "_csrf-backend":csrfToken});

                            $.post(
                                "<?=Url::to(['deleterole'])?>"
                                ,{ name:result, "_csrf-backend":csrfToken}
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
                        });
                    }
                break;
            };
        });

        //监听行工具栏事件
        table.on('tool(roles)', function(obj){
            switch(obj.event) {
                case 'delete':
                    layer.confirm('确定删除 '+obj.data.description+' 角色?', function(index){
                        $.post(
                            "<?= Url::to(['deleterole'])?>"
                            ,{name:obj.data.name, "_csrf-backend":csrfToken}
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

                        // 关闭窗口
                        layer.close(index);

                    });
                break;
            };
        });


        // 监听搜索框
        form.on('submit(search)', function(data){
            console.log(data.field.name);
            //console.log(data.field.email);
            //根据搜索条件重载表格
            indexTable.reload({
                where:{
                    "_csrf-backend":csrfToken
                    ,"name":data.field.name
                }
            })
            layer.msg("search");
            return false;
        });

    });


<?php
JsBlock::end();
?>