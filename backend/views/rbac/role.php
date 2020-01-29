<?php

use yii\helpers\Html;
use backend\models\User;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['roles']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<form  class="layui-form layui-form-pane">
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app', 'Name')?></label>
        <div class="layui-input-block">
            <input type="text" name="Role[name]" value="<?=$model->name?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app', 'Description')?></label>
        <div class="layui-input-block">
            <input type="text" name="Role[description]" value="<?=$model->description?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app', 'Rule')?></label>
        <div class="layui-input-block">
            <input type="text" name="Role[rule]" value="<?=$model->ruleName?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=Yii::t('app', 'Data')?></label>
        <div class="layui-input-block">
            <input type="text" name="Role[data]" value="<?=$model->data?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>
</form>


<?php 
    JsBlock::begin();
    // 使用Layui 的form模块，用来渲染登录表格
    // Layui 在LoginAsset中已经加载
?>
    layui.use('form',function(){

    });
<?php
    JsBlock::end();
?>