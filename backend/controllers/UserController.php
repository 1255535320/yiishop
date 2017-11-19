<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\LoginForm;
use backend\models\PasswordFrom;
use backend\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    public function actionList()
    {
        $model = User::find()->all();
        return $this->render('index', ['model' => $model]);
    }

    //添加管理员
    public function actionAdd()
    {
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $model = new User();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //密码加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //获取ip
//                 $model->last_login_ip=\Yii::$app->request->getUserIP();
                $model->last_login_ip = \Yii::$app->request->userIP;
                //添加用户到数据表
//                $auth->add($model);
                $roles = $request->post("User")['roles'];
//                var_dump($roles);
                //提交
                $model->save();
                foreach ($roles as $role) {
                    //获取角色
                    $roless = $auth->getRole($role);
                    $auth->assign($roless, $model->id);
                }
                //提示并且跳转
                \Yii::$app->session->setFlash('warning', '添加成功');
                return $this->redirect('list');
            }
        }
        //获取角色
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles, "name", "description");
        return $this->render('add', [
            'model' => $model,
            'roles' => $roles,
        ]);

    }

    //修改密码
    public function actionPassword()
    {
        $request = new Request();
        //实例化表单模型
        $password = new PasswordFrom();
        if ($request->isPost) {
            //接收数据
            $password->load($request->post());
            //验证
            if ($password->validate()) {
                //获取旧密码
                $password_hash = \Yii::$app->user->identity->password_hash;
                //验证旧密码
                if (\Yii::$app->security->validatePassword($password->oldpassword, $password_hash)) {
//                    $model->password_hash=$password->newpassword;
                    //根据id修改密码(使用hash加密)
                    User::updateAll([
                        'password_hash' => \Yii::$app->security->generatePasswordHash($password->newpassword)
                    ],
                        ['id' => \Yii::$app->user->id]
                    );
                    //注销并且跳转
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('warning', '修改成功,请重新登录');
                    return $this->redirect('login');
                } else {
                    //验证旧密码失败
                    $password->addError('oldpassword', '旧密码不正确');
                }
            }
        }
        //判断用户是否登录
        if (\Yii::$app->user->id) {
            return $this->render('password', ['password' => $password]);
        } else {
            \Yii::$app->session->setFlash('warning', '请登录后操作');
            return $this->redirect('login');

        }

    }

    //删除管理员
    public function actionDelete($id)
    {
        $auth = \Yii::$app->authManager;
//        $rule=$auth->getRolesByUser($id);
//        echo 111;
//        var_dump($rule);exit;
        $model = User::findOne(['id' => $id]);
        $act = [];
        if ($model) {
            $model->delete();
            $auth->revokeAll($id);
            $act = 1;
//            $auth->remove($rule);

        }
        echo json_encode($act);
    }

    //修改管理员信息
    public function actionUpdate($id)
    {
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $model = User::findOne(['id' => $id]);
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //获取ip
//                 $model->last_login_ip=\Yii::$app->request->getUserIP();
                $model->last_login_ip = \Yii::$app->request->userIP;
                $roles = $request->post("User")['roles'];
                //提交
                $model->save();
                foreach ($roles as $role) {
                    //获取角色
                    $roless = $auth->getRole($role);
                    $auth->assign($roless, $model->id);
                }
                //提示并且跳转
                \Yii::$app->session->setFlash('warning', '修改成功');
                return $this->redirect('list');
            }
        }
        $roless = $auth->getRolesByUser($id);//取出用户的权限
//        var_dump($roless);exit;
        $roles = $auth->getRoles();//所有权限
        $roles = ArrayHelper::map($roles, "name", "description");//所有的
        //$model->roles=['sada','就不会吧'];
        $model->roles = array_keys($roless);
        return $this->render('add', [
            'model' => $model,
            'roles' => $roles,
        ]);

    }

    //管理员登录
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = new Request();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //验证信息
            if ($model->validate()) {
                //账号密码--调用loginfangfa
                if ($model->login()) {
                    //验证成功
//                    var_dump(111);exit;
                    \Yii::$app->session->setFlash('success', '登陆成功');
                    return $this->redirect('list');
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    //管理员注销登录
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect('login');

    }

//    过滤器
//    public function behaviors()
//    {
//        return [
//            'rbac' => [
//                'class' => RbacFilter::className(),
//                'except'=>['login']
//            ]
//        ];
//    }

}
