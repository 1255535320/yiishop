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
    public function actionMenu_add(){
        $request=new Request();
        $model=new Menu();
        //给父id一个默认值
//        $model->top_menu=0;
        if ($request->isPost){
            $model->load($request->post());
           if($model->validate()){
               //判断层级
            if ($model->top_menu==0){
                //创建父节点
                $model->makeRoot();
            }else{
                //创建子节点
                $parent= Menu::findOne(['id'=>$model->top_menu]);//查找父id
                $model->prependTo($parent);
            }//提示并且跳转
               \Yii::$app->session->setFlash('success', '添加成功');
               return $this->redirect('menu_list');
           }
        }
        return $this->render('add',['model'=>$model]);
    }
    //商品列表
    public function actionMenu_list(){
        $model=Menu::find()->addOrderBy(['tree'=>'desc','lft'=>'asc','rgt'=>'asc'])->all();
        return $this->render('index',['model'=>$model]);
    }
    //商品修改
    public function actionMenu_update($id){
        $request=new Request();
        $model=Menu::findOne($id);
        //判断请求方式
        if ($request->isPost){
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //判断层级
                //获取到的父id为0(修改根节点或者创建根节点)
                if ($model->top_menu==0){
                    //如果旧的父id为0,说明在修改根节点(根节点-根节点)
                    if ($model->getOldAttribute('top_menu')==0){
                        $model->save();//用save修改
                    }else{
                        //否则就是把子节点修改为根节点
                        $model->makeRoot();//创建根节点
                    }
                }else{
                    //子节点
                }
            }




        }
        return $this->render('add',['model'=>$model]);
    }

    //菜单删除
    public function actionMenu_delete($id){
        $model=Menu::findOne($id);
        $act=[];
        if ($model->isLeaf()){//判断是否为根节点
            if ($model->top_menu!=0){
                //无根节点,直接删除
                $model->delete();
            }else{
                $model->deleteWithChildren();
            }
            $act=1;
        }
        echo json_encode($act);
    }

}