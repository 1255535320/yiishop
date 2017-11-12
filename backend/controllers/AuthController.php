<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 11:52
 */

namespace backend\controllers;


use backend\models\AuthItem;
use backend\models\AuthRule;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class AuthController extends Controller
{
    //添加权限
    public function actionAdd_item()
    {
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $model = new AuthItem();
        if ($request->isPost) {
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //创建权限
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->description;
                //添加到数据库
                $auth->add($permission);
                \Yii::$app->session->setFlash('warning', '添加成功');
                return $this->redirect('item_list');
            }
        }
        return $this->render('additem', ['model' => $model]);
    }

    //权限列表
    public function actionItem_list()
    {
        $model = AuthItem::find()->where(['type' => 2])->all();
//        var_dump($model);exit;
        return $this->render('item_list', ['model' => $model]);
    }

    //修改权限
    public function actionItem_update($name)
    {
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $model = AuthItem::findOne(['name' => $name]);
        $permission = $auth->getPermission($name);
        if ($request->isPost) {
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //更新权限
                $permission->name = $model->name;
                $permission->description = $model->description;
                //数据库更新
               $auth->update($name,$permission);
                \Yii::$app->session->setFlash('warning', '添加成功');
                return $this->redirect('item_list');
            }
        }
        return $this->render('additem', ['model' => $model]);

    }

    //权限删除
    public function actionItem_delete($name)
    {
        $auth = \Yii::$app->authManager;
        $permission = $auth->getPermission($name);
        $act = [];
        if ($permission) {
            $auth->remove($permission);
            $act = 1;
        }
        echo json_encode($act);
    }

    //添加角色
    public function actionAdd_role()
    {
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $model = new AuthRule();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //创建角色
                $role = $auth->createRole($model->name);
                $role->description = $model->description;
                //角色添加到数据表
                $auth->add($role);
                foreach ($model->permissions as $permissionName) {
                    //根据权限名获取权限对象
                    $permission = $auth->getPermission($permissionName);
                    //给角色分配权限
//                    echo 111;exit;
                    $auth->addChild($role, $permission);
                    \Yii::$app->session->setFlash('warning', '添加成功');
                    return $this->redirect('role_list');
                }
            }
        }
        //获取权限和注释
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('add_role', [
            'model' => $model,
            'permissions' => $permissions,
        ]);

    }

    //角色列表
    public function actionRole_list()
    {
        $model = AuthItem::find()->where(['type' => 1])->all();
        return $this->render('role_list', ['model' => $model]);
    }

    //角色修改
    public function actionRole_update($name)
    {
        $request = new Request();
        $auth = \Yii::$app->authManager;
        $model = AuthRule::findOne(['name' => $name]);
        //var_dump($model);exit;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //创建角色
                $role = $auth->getRole($model->name);
                $role->description = $model->description;
                //角色添加到数据表
                $auth->add($role);
                foreach ($model->permissions as $permissionName) {
                    //根据权限名获取权限对象
                    $permission = $auth->getPermission($permissionName);
                    //给角色分配权限
//                    echo 111;exit;
                    $auth->addChild($role, $permission);
                    \Yii::$app->session->setFlash('warning', '添加成功');
                    return $this->redirect('role_list');
                }
            }
        }
        //获取权限和注释
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('add_role', [
            'model' => $model,
            'permissions' => $permissions,
        ]);
    }

    //角色移除
    public function actionRole_delete($name)
    {
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        $act = [];
        if ($role) {
            $auth->remove($role);
            $act = 1;
        }
        echo json_encode($act);
    }
    //
}