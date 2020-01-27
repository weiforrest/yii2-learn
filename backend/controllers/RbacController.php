<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

class RbacController  extends Controller
{

    //Role
    public function actionRoles()
    {
        

    }

    public function actionCreaterole()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->createRole(null);
        return $this->render('_createitem');

    }

    public

}