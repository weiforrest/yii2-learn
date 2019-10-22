<?php
/**
 * 
 * 
 */
namespace console\controllers;
use backend\models\Admin;

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
}