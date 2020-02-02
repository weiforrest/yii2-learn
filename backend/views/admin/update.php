<?php

use yii\helpers\Url;
use backend\models\Admin;
use common\widgets\JsBlock;
use yii\helpers\HTML;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = Yii::t('app', 'Update Admin: {name}', [
    'name' => $model->nickname,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nickname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<form  class="layui-form layui-form-pane" action="<?= Url::to(["admin/update",'id' => $model->id])?>" method="post">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken(); ?>">
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('username'))?></label>
        <div class="layui-input-block">
            <input type="text" name="Admin[username]" value="<?=$model->username?>" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input" lay-verType="tips">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('nickname'))?></label>
        <div class="layui-input-block">
            <input type="text" name="Admin[nickname]" value="<?=$model->nickname?>" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input" lay-verType="tips">
        </div>
    </div>
    <div class="layui-form-item" >
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('password'))?></label>
        <div class="layui-input-block">
            <input type="password" name="Admin[password]" value="<?=$model->password?>"  placeholder="" autocomplete="off" class="layui-input" lay-verType="tips">
        </div>
    </div>
    <div class="layui-form-item" >
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('repassword'))?></label>
        <div class="layui-input-block">
            <input type="password" name="Admin[repassword]" value="<?=$model->repassword?>"  placeholder="" autocomplete="off" class="layui-input" lay-verType="tips">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('status'))?></label>
        <div class="layui-input-block">
            <input type="checkbox" name="User[status]" lay-skin="switch" lay-text="Active|InActive" lay-filter="status" value="<?=$model->status?>" <?=$model->status == Admin::STATUS_ACTIVE ? 'checked':''?>>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="">提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<?php JsBlock::begin();
    // 使用Layui 的form模块，用来渲染登录表格
    // Layui 在LoginAsset中已经加载
?>
    //监听status的切换
    layui.use('form',function(){
        var form = layui.form;

        form.on('switch(status)', function(data){
            console.log(data);
            data.elem.value = data.elem.checked ? '<?=Admin::STATUS_ACTIVE?>' : '<?=Admin::STATUS_INACTIVE?>';
            data.elem.checked = true; //因为不选中不传递值给后台，所有设置为选中, 为了传递值给后台
        });

    });
<?php
    /* 使用setFlash 传递错误信息
    if($model->errors){
        // 取出验证中获取到的第一个错误，用layer显示
        $value = current($model->getFirstErrors());
?>
    layui.use('layer',function(){
        layer.msg("<?= HTML::encode($value)?>",{icon:5});
    });
<?php
    }*/
JsBlock::end();
?>