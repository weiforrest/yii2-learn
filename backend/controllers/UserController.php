<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    

    /**
     * Lists all User models.
     * @return  viewfile
     */
    public function actionIndex()
    {
        return $this->render('index',[
        ]);
    }


    /**
     * Lists all User models.
     * 因为layui.table 请求格式问题,无法通过csrf认证,单独一个控制器返回数据
     * @return json
     */
    public function actionData()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->bodyParams);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        return [
            "code" => 0,
            'msg' => "OK",
            'count' => $dataProvider->getTotalCount(),
            'data' => $dataProvider->getModels(),
        ];
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => User::SCENARIO_REGISTER]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionStatus()
    {
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',null);
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            Yii::trace($id);
            if($id) {
                $model = $this->findModel($id);
                if($model){
                    $status = Yii::$app->request->post('status',null);
                    Yii::trace($status);
                    $model->status = $status;
                    if($model->save()){
                        $code = 0;
                        $msg = '更新成功';
                    }else{
                        $code = 1;
                        $errorReasons = $model->getErrors();
                        foreach($errorReasons as $errorReason) {
                            $msg .= $errorReason[0] . ';';
                        }
                    }
                } else{
                    $code = 1;
                    $msg = '没有找到id='.$id.'记录';
                }
            } else{
                $code = 1;
                $msg = '没有获取到参数id';
            }
            return [
                "code" => $code,
                'msg' => $msg,
            ];
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        if(Yii::$app->request->isAjax){
            $ids = Yii::$app->request->post('id');
            if($ids !== null){
                // $model = $this->findModel($id);
                // $model->status = User::STATUS_INACTIVE;
                $errors = [];
                // 将单个请求转化为数组
                Yii::trace($ids);
                if(is_array($ids)){
                    Yii::trace("is array");
                }else{
                    $ids=[$ids];
                    Yii::trace("not is array");
                    Yii::trace($ids);
                }
                // !isset($ids[0]) && $ids = [$ids];
                $err = '';
                foreach($ids as $id) {
                    $model = $this->findModel($id);
                    if($model !== null){
                        $result = $model->delete();
                        if(!$result){
                            $errors[$id] =$model;
                        }
                    }else{
                        $err.= 'id='.$id.'not found<br>';
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
                    foreach($errors as $one => $model) {
                        $err .=$one .':';
                        $errorReasons = $model->getErrors();
                        foreach($errorReasons as $errorReason) {
                            $err .= $errorReason[0] . ';';
                        }
                        $err = rtrim($err, ';') . '<br>';
                    }
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

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        if(Yii::$app->request->isAjax){// ajax 请求不宜直接抛出错误 前台获取不到。
            return null;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
