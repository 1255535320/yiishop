<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 10:11
 */

namespace frontend\controllers;


use frontend\models\Address;
use yii\web\Controller;
use yii\web\Request;

class AddressController extends Controller
{
    //添加收货地址
    public function actionAdd()
    {
//        var_dump(\Yii::$app->user->id);exit;
        $request = new Request();
        $model = Address::findAll(['member_id'=>\Yii::$app->user->getId()]);
        $model1=new Address();
        if ($request->isPost) {
            //接收数据
            $model1->load($request->post(), '');
//            var_dump($model);exit;
            //验证
            if ($model1->validate()) {
                $model1->member_id = \Yii::$app->user->getId();
//                var_dump($model);exit;
                $model1->save();
                return $this->redirect('add');
//                echo 111;exit;
            }else{
                var_dump($model1->getErrors());exit();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //列表
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest){
            echo '请登录后操作';
            return $this->redirect('/member/login');
        }
        //
       $id= \Yii::$app->user->getId();
        $model = Address::find()->where(['member_id'=>$id])->all();
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionUpdate($id)
    {
        $request = new Request();
        $model1 = Address::findOne($id);
        $model = Address::findAll(['member_id'=>\Yii::$app->user->getId()]);
        //var_dump($model);exit;
        if ($request->isPost) {
            //接收数据
//             $model = new Address();
            $model1->load($request->post(), '');
//            var_dump($model);exit;
            //验证
            if ($model1->validate()) {
//                $model->member_id = \Yii::$app->user->getId();
                $model1->save();
                return $this->redirect('index');
            }
        }
        return $this->render('add', ['model1' => $model1,
            'model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model = Address::findOne($id);
        if ($model){
            $model->delete();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect('add');
        }
    }
};