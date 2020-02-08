<?php

use yii\helpers\Html;
use backend\models\Role;
use yii\helpers\Url;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['roles']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['rbac/role', 'name' => $model->name]];
\yii\web\YiiAsset::register($this);

?>
<form  class="layui-form layui-form-pane" action="<?= Url::to(["rbac/role", 'name' => $model->name])?>" method="post">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken(); ?>">
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
    <div class="layui-form-item" >
        <label class="layui-form-label"><?=Yii::t('app', 'Permissions')?></label>
        <div class="layui-input-block">
        <input type="checkbox" name="permission_all" title="全选">
        </div>
    </div>
        <?php
            foreach($permissions as $groupName => $groupPermissions){
                $i=0;
                foreach($groupPermissions as $permission){
                    $checked = in_array($permission, $rolePermissions) ? true:false;
                    if($i==0){
                        echo '<input type="checkbox"  name="Permissions['.$permission->name.']" title="'.$permission->description.'"'. ($checked ? 'checked':'') .' >';
                        echo '<div class="layui-input-block">';
                        $i=1;
                    }else{
                        echo '<input type="checkbox"  name="Permissions['.$permission->name.']" title="'.$permission->description.'"'. ($checked ? 'checked':'') .' >';
                    }
                }
                $i=0;
                echo '</div>';
            }

        ?>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="">提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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