<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Role;
use yii\data\ArrayDataProvider;

class RbacController  extends Controller
{

    //Role
    public function actionRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        //处理分页
        $pagination = [];
        $params = Yii::$app->request->bodyParams;
        $pagination['pageSize'] = $params['pageSize'] ? $params['pageSize']: 10;

        if($params['page']){
            // Yii2 current page number is zero-based
            $pagination['page'] = $params['page'] - 1;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => $pagination,
        ]);


        return $this->render('roles');
    }

    public function actionCreaterole()
    {
        $model = new Role();
        if ($model->load(Yii::$app->request->post())){
           $auth = Yii::$app->authManager;
           $role = $auth->createRole($model->name);
           $role->description = $model->description;
        //    var_dump($model);
        //    exit();

           if($auth->add($role)) {
                Yii::$app->getSession()->setFlash("success", Yii::t('app',"Success"));
                return $this->redirect(['role', 'id' => $role->name]);
           }else{
                $errors = $model->getErrors();
                $err = '';
                foreach($errors as $error){
                    $err .= $error[0] . '<br>';
                }
                Yii::$app->getSession()->setFlash('error', $err);
            }
        }
        return $this->render('createrole',['model' => $model]);

    }

    public function actionRole($name)
    {


    }


}