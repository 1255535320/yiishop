<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 15:40
 */

namespace backend\controllers;


use backend\models\ArticleCategory;
use yii\web\Controller;
use yii\web\Request;

class Article_categoryController extends Controller
{
    //添加文章分类
    public function actionAdd()
    {
        $request = new Request();
        $model = new ArticleCategory();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                $model->save();
                //提醒并跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list');
            }
        } else {
            return $this->render('add', ['model' => $model]);
        }
    }

    //分类列表
    public function actionList()
    {
        $model = ArticleCategory::find()->all();
        return $this->render('index', ['model' => $model]);

    }
    //文章分类修改
    //品牌修改
    public function actionUpdate($id)
    {
        $request = new Request();
        $model = ArticleCategory::findOne(['id' => $id]);
        //判断请求方式
        if ($request->isPost) {
            //获取数据
            $model->load($request->post());
            //把图片封装
            if ($model->validate()) {
                $model->save();
                //提示并跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect('list');
            }
            //展示修改表单
        }
        return $this->render('add', ['model' => $model]);
    }
    //删除文章分类
    public function actionDelete($id)
    {
        $result = ArticleCategory::findOne($id);
        $act = [];
        if ($result) {
            $result->delete();
            $act = 1;
        }
        echo json_encode($act);
    }
}