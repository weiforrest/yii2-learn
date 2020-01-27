<?php

use yii\helpers\Html;
use backend\models\User;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<form  class="layui-form layui-form-pane">
    <div class="layui-form-item">
        <label class="layui-form-label"><?=$model->getAttributeLabel('username')?></label>
        <div class="layui-input-block">
            <input type="text" name="User[username]" value="<?=$model->username?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=$model->getAttributeLabel('email')?></label>
        <div class="layui-input-block">
            <input type="email" name="User[email]" value="<?=$model->email?>"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?=$model->getAttributeLabel('status')?></label>
        <div class="layui-input-block">
            <input type="checkbox" name="User[status]" lay-skin="switch" lay-text="Active|InActive" disabled <?= $model->status == User::STATUS_ACTIVE? 'checked':''?> >
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