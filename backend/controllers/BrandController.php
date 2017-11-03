<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 14:07
 */

namespace backend\controllers;


use backend\models\Brand;
use yii\base\Component;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    //添加品牌
    public function actionAdd(){
        $request = new Request();
        $model = new Brand();
        //判断请求方式
        if ($request->isPost){
            //获取数据
            $model->load($request->post());
            //把图片封装
            $model->imgfile=UploadedFile::getInstance($model,'imgfile');
            if ($model->validate()){
                //获取图片后缀
                $ext=$model->imgfile->extension;
                //拼接存储路径
                $file='/upload/'.uniqid().'.'.$ext;
                //图片保存
                $model->imgfile->saveAs(\Yii::getAlias('@webroot'.$file,0));
                //上传
                $model->logo=$file;
                $model->save(false);
                //提示并跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('list');
            }

        }
        //展示添加表单
        return $this->render('add',['model'=>$model]);

    }
    //展示表单
    public function actionList(){
        $model=Brand::find()->all();
        return $this->render('index',['model'=>$model]);

    }
    //品牌修改
    public function actionUpdate($id){
        $request= new Request();
        $model=Brand::findOne(['id'=>$id]);
        //判断请求方式
        if ($request->isPost) {
            //获取数据
            $model->load($request->post());
            //把图片封装
            $model->imgfile = UploadedFile::getInstance($model, 'imgfile');
            if ($model->validate()) {
                //获取图片后缀
                $ext = $model->imgfile->extension;
                //拼接存储路径
                $file = '/upload/' . uniqid() . '.' . $ext;
                //图片保存
                $model->imgfile->saveAs(\Yii::getAlias('@webroot' . $file, 0));
                //上传
                $model->logo = $file;
                $model->save(false);
                //提示并跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect('list');
            }  //展示添加表单

        }
        return $this->render('add',['model'=>$model]);
    }
    //删除品牌
    public function actionDelete($id){
       $result= Brand::findOne($id)->delete();
       $act=[];
       if ($result){
           $act=1;
       }
       echo json_encode($act);
    }
}