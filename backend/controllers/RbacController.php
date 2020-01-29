<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Role;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class RbacController  extends Controller
{

    //Role
    public function actionRoles()
    {
        if(Yii::$app->request->isAjax){
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
                'allModels' => $roles,
                'pagination' => $pagination,
            ]);

            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;

            //处理json数据,如果键不用数字，导致前台页面选择失效
            $data = $dataProvider->getModels();
            $roles = [];
            foreach($data as $role){
                $roles[] = $role;
            }

            return [
                "code" => 0,
                'msg' => "OK",
                'count' => $dataProvider->getTotalCount(),
                'data' => $roles,
            ];

        } else {
            return $this->render('roles');
        }

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
                return $this->redirect(['role', 'name' => $role->name]);
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

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        if($role){
            return $this->render('role', [
                'model' => $role,
            ]);
        }else{
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionDeleterole()
    {
        if(Yii::$app->request->isAjax){
            $names = Yii::$app->request->post('name');
            if($names !== null){
                // $model = $this->findModel($id);
                // $model->status = User::STATUS_INACTIVE;
                $errors = [];
                // 将单个请求转化为数组
                Yii::trace($names);
                if(is_array($names)){
                    Yii::trace("is array");
                }else{
                    $names=[$names];
                    Yii::trace("not is array");
                    Yii::trace($names);
                }
                // !isset($ids[0]) && $ids = [$ids];
                $err = '';
                foreach($names as $name) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole($name);
                    if($role !== null){
                        $result = $auth->remove($role);
                        if(!$result){
                            $errors[$name] =$role;
                        }
                    }else{
                        $err.= 'name='.$name.'not found<br>';
                    }
                }
                $response = Yii::$app->response;
                $response->format = \yii\web\Response::FORMAT_JSON;
                if (count($errors) == 0 &&  $err === '') {
                    return [
                        "code" => 0,
                        'msg' => "删除成功",
                    ];
                } else {
                    // foreach($errors as $one => $model) {
                    //     $err .=$one .':';
                    //     $errorReasons = $model->getErrors();
                    //     foreach($errorReasons as $errorReason) {
                    //         $err .= $errorReason[0] . ';';
                    //     }
                    //     $err = rtrim($err, ';') . '<br>';
                    // }
                    $err = rtrim($err, '<br>');

                    return [
                        "code" => 1,
                        "msg" => $err,
                    ];
                }
            }
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


}