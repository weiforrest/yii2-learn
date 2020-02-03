<?php
/**
 * 在Docker中使用
 * docker-compose exec php /app/yii init/rbac
 * 
 */
namespace console\controllers;
use backend\models\Admin;
use Yii;

class InitController extends \yii\console\Controller
{
    /**
     * Create init user
     */
    public function actionAdmin()
    {

        echo "Create init Administor\nUserName: admin\nPassword: admin\n";
        $model = new Admin;
        $model->username = "admin";
        $model->nickname = "超级管理员";
        $model->setPassword("admin");
        $model->status = Admin::STATUS_ACTIVE;
        if($model->save()) {
            echo "Successfully.\n";
        }
        return 0;
    }

    /**
     * 根据后台创建各种权限
     */
    public function actionRbac()
    {
        $trans = Yii::$app->db->beginTransaction();
        try{
            $dir = dirname(dirname(dirname(__FILE__))) . '/backend/controllers';
            $controllers = glob($dir.'/*');
            //遍历控制器
            $permissions = [];
            foreach($controllers as $controller) {
                $content = file_get_contents($controller);
                \preg_match('/class ([a-zA-Z]+)Controller/', $content,$match);
                $controllerName = $match[1];
                $actionPermissions = [];
                $actionPermissions[] = \strtolower($controllerName. '/*');
                //匹配首字母大写的动作,防止匹配到actions方法
                \preg_match_all('/public function action([A-Z][a-zA-Z]+)/', $content, $matches);
                // 遍历控制器文件
                foreach($matches[1] as $actionName){
                    $actionPermissions[] = \strtolower($controllerName.'/'. $actionName);
                }
                $permissions[$controllerName] = $actionPermissions;

            }
            $auth = Yii::$app->authManager;
            foreach($permissions as $controllerName => $actionPermissions) {
                foreach($actionPermissions as $permission)
                if(!$auth->getPermission($permission)) {
                    $obj = $auth->createPermission($permission);
                    $obj->description = $permission;
                    $obj->data = $controllerName;

                    $auth->add($obj);
                }
            }
            $trans->commit();
            echo '创建成功';

        } catch(\Exception $e){
            $trans->roleback();
            echo '创建失败';
        }

    }

}