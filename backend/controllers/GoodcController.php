<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsQuery;
use yii\web\Request;
use yii\widgets\Menu;

class GoodcController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=GoodsCategory::find()->addOrderBy(['tree'=>'desc','lft'=>'asc','rgt'=>'asc'])->all();
        return $this->render('index',['model'=>$model]);
    }
    //添加
    public function actionAdd()
    {
        $request = new Request();
        $model = new GoodsCategory();
        //给父id一个默认值
        $model->parent_id=0;
        //判断请求方式
        if ($request->isPost) {
            $model->load($request->post());
            if($model->validate()){
//                $model->save();不能吃用此方法
                //判断层级
                if ($model->parent_id==0){
                    //创建根节点
//                $countries = new Menu(['name' => 'Countries']);
                    $model->makeRoot();exit;
                }
                //添加子节点
//                $russia = new Menu(['name' => 'Russia']);
                //根据父节点添加
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }
            //提示并跳转
            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionUpdate($id){
        $request = new Request();
        $model= GoodsCategory::findOne($id);
        //判断请求方式
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
//                $model->save();不能用此方法
                //判断层级
                if ($model->parent_id == 0) {
                    //修改根节点
                    if ($model->getOldAttribute('parent_id') == 0) {
                        $model->save();
                    } else {
                        //如果是子节点,直接修改
                        $model->makeRoot();//此方法不能修改根节点
                    }
                }else {
                    //添加子节点
                    //根据父节点添加
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);
                }
                //提示并且跳转//提示并跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除分类
    public function actionDelete($id){

      $result=  GoodsCategory::findOne($id);
      $act=1;
      //ztree自带的判断是否有子节点方法
    if ($result->isLeaf()){
        if ($result->parent_id !=0){
            $result->delete();
        }else{
            $result->deleteWithChildren();//删除根节点和下面的子节点
        }
    }
      echo json_encode($act);
    }
}
