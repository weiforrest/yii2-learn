<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

// use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\JsBlock;

$this->title = 'Signup';
?>
<div class="layadmin-user-login layadmin-user-display-show" id="lay-user-login">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2><?= Yii::$app->name;?></h2>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body">
            <form class="layui-form" action="<?= Url::to(['site/signup']);?>" method="post">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken(); ?>">
                <div class="login-form-title"></div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="lay-user-login-username"></label>
                    <input type="text" name="SignupForm[username]" id="layui-user-login-username"  lay-verify="required" placeholder="用户名" class="layui-input" value="<?=$model->username?>" lay-verType="tips">
                </div>

                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="lay-user-login-username"></label>
                    <input type="text" name="SignupForm[email]" id="layui-user-login-email"  lay-verify="required" placeholder="电子邮箱" class="layui-input" value="<?=$model->email?>" lay-verType="tips">
                </div>

                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="lay-user-login-password"></label>
                    <input type="password" name="SignupForm[password]" id="layui-user-login-password" lay-verify="required" placeholder="密码" class="layui-input" value="<?=$model->password?>" lay-verType="tips">
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="lay-user-login-submit">注册</button>
                </div>
            </form>
        </div>
    </div>
    <div class="layui-trans layadmin-user-login-footer">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</div>
<?php JsBlock::begin(); ?>
    layui.use('form',function(){});
<?php
    if($model->errors){
        // 取出验证中获取到的第一个错误，用layer显示
        $value = current($model->getFirstErrors());
?>
    layui.use('layer',function(){
        layer.msg("<?= $value?>",{icon:5});
    });
<?php 
    }
JsBlock::end();
?>