<?php

use yii\helpers\Url;
use common\widgets\JsBlock;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>


<form  class="layui-form layui-form-pane" action="<?= Url::to(["admin/create"])?>" method="post">
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
            <input type="password" name="Admin[password]" value="<?=$model->password?>" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input" lay-verType="tips">
        </div>
    </div>
    <div class="layui-form-item" >
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('repassword'))?></label>
        <div class="layui-input-block">
            <input type="password" name="Admin[repassword]" value="<?=$model->repassword?>" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input" lay-verType="tips">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app',$model->getAttributeLabel('status'))?></label>
        <div class="layui-input-block">
            <input type="checkbox" name="Admin[status]" lay-skin="switch" lay-text="Active|InActive" value="10">
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
    layui.use('form',function(){

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