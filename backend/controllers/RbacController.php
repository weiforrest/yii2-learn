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

           if($auth->add($role)) {
               $permissions = Yii::$app->request->post('Permissions');
               foreach($permissions as $key => $val) {
                   if($permission = $auth->getPermission($key)) {
                        // 分配权限
                        if($auth->canAddChild($role,$permission)){
                            $auth->addChild($role, $permission);
                        }

                    }
                }
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
        
        $permissions = $this->getPermissions();
        return $this->render('createrole',[
            'model' => $model,
            'permissions' => $permissions,
        ]);

    }

    public function actionRole($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if($role){
            $model = new Role();
            // 处理更新提交
            if ($model->load(Yii::$app->request->post())){
               $copyPermissions = Yii::$app->request->post('Permissions');
               $permissions = $copyPermissions;
               //更新角色信息
               if($role->description != $model->description || $role->name != $model->name){
                   $role->description = $model->description;
                   if($role->name != $model->name){
                        if($auth->getRole($model->name)){
                                Yii::$app->getSession()->setFlash('error', 'Role: '.$model->name.' is existed.');
                                $rolePermissions = $auth->getPermissionsByRole($role->name);
                                $permissions = $this->getPermissions();
                                return $this->render('role', [
                                    'model' => $role,
                                    'permissions' => $permissions,
                                    'rolePermissions' => $rolePermissions,
                                ]);
                        }
                    }
                   
                   $role->name = $model->name;
                   $auth->update($name,$role);
               }
            
                $rolePermissions = $auth->getPermissionsByRole($model->name);
                // var_dump($rolePermissions);
                // var_dump($permissions); echo "<br/>";
                // exit();

                // 先找到需要删除的权限，再找到需要添加的
                foreach($rolePermissions as $permission){
                    if(!isset($permissions[$permission->name])){
                        //删除权限
                        $removePermission = $auth->getPermission($permission->name);
                        // echo "删除权限".$permission->name;
                        $auth->removeChild($role,$removePermission);
                    } else {
                        // 权限相同
                        $permissions[$permission->name] = false;
                    }
                }
                // var_dump($permissions);
                // exit();
                // 添加权限
                if($permissions){
                    foreach($permissions as $permissionName => $value) {
                        if($value){
                            if($permission = $auth->getPermission($permissionName)) {
                                if($auth->canAddChild($role,$permission)){
                                    $auth->addChild($role, $permission);
                                    // echo '添加权限'. $permissionName;
                                }

                            }
                        }
                    }
                }
                Yii::$app->getSession()->setFlash("success", Yii::t('app',"Success"));

            } 

            $rolePermissions = $auth->getPermissionsByRole($role->name);
            $permissions = $this->getPermissions();
            // 获取当前权限

            return $this->render('role', [
                'model' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $rolePermissions,
            ]);
        }else{
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function getPermissions()
    {
        $auth = Yii::$app->authManager;
        // 获取所有权限
        $originPermissions = $auth->getPermissions();
        /*
        $result = [];
        foreach($permissions as $permission) {
            if($auth->canAddChild($role,$permission)){
                // $auth->addChild($role,$permission);
                $result[] = [$permission->name => $permission->description];
            }
        }*/

    
        // 对所有的权限进行分组
        $permissions = [];
        foreach($originPermissions as $k => $val){
            $permissions[$val->data][] = $val;
        }
        return $permissions;

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