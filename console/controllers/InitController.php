<?php
/**
 * 
 * 
 */
namespace console\controllers;
use common\models\User;

class InitController extends \yii\console\Controller
{
    /**
     * Create init user
     */
    public function actionUser()
    {

        echo "Create init Administor\nUserName: admin\nPassword: admin\n";
        $model = new User;
        $model->username = "admin";
        $model->email = "admin@admin.com";
        $model->setPassword("admin");
        $model->generateAuthKey();
        $model->status = User::STATUS_ACTIVE;
        $model->generateEmailVerificationToken();
        if($model->save()) {
            echo "Successfully.\n";
        }
        return 0;
    }
}