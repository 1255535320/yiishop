<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 13:57
 */

namespace backend\controllers;


use backend\models\AuthItem;
use backend\models\GoodsCategory;
use backend\models\Menu;
use Qiniu\Auth;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller
{
    //添加菜单
    public function actionAdd_menu(){
        $request=new Request();
        $model=new Menu();
        //给父id一个默认值
        $model->top_menu=0;
        if ($request->isPost){
            $model->load($request->post());
           if($model->validate()){
               //判断层级
            if ($model->top_menu==0){
                //创建父节点
                $model->makeRoot();
            }else{
                //创建子节点
                $parent= GoodsCategory::findOne(['id'=>$model->top_menu]);//查找父id
                $model->prependTo($parent);
            }//提示并且跳转
               \Yii::$app->session->setFlash('success', '添加成功');
               return $this->redirect('index');
           }
        }
        return $this->render('index',['model'=>$model]);
    }
}