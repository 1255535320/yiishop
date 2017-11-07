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
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;



class BrandController extends Controller
{
    public $enableCsrfValidation =false;
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
//                $model->logo=$file;
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
//            $model->imgfile = UploadedFile::getInstance($model, 'imgfile');
            if ($model->validate()) {
                //获取图片后缀
//                $ext = $model->imgfile->extension;
                //拼接存储路径
//                $file = '/upload/' . uniqid() . '.' . $ext;
                //图片保存
//                $model->imgfile->saveAs(\Yii::getAlias('@webroot' . $file, 0));
                //上传
//                $model->logo = $file;
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
    //处理ajax图片
    public function actionUpload(){
        //判断
        if (\Yii::$app->request->isPost){
            $imgfile = UploadedFile::getInstanceByName('file');
            if ($imgfile){
                $file ='/upload/'.uniqid().'.'.$imgfile->extension;
                $imgfile->saveAs(\Yii::getAlias('@webroot').$file,0);
//                return Json::encode(['url'=>$file]);
                //将图片上传到七牛云
                // 引入上传类
// 需要填写你的 Access Key 和 Secret Key
                $accessKey ="T_yCFxTU_V7c9H8T_mxP4BAxUgsmGV3O7NQ92tF6";
                $secretKey = "S2DTBEg9j2SrsHbfkTXUmqRuG4xLVRD38LRywnWl";
                $bucket = "zl1991";
                $domian='oyy6y1nsc.bkt.clouddn.com/';
// 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);
// 生成上传 Token
                $token = $auth->uploadToken($bucket);
// 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot').$file;
// 上传到七牛后保存的文件名
                $key = $file;
// 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//echo "\n====> putFile result: \n";
                if ($err !== null) {
                    //上传失败
//                    var_dump($err);
                    return Json::encode(['error'=>$err]);
                } else {
                    //上传成功
//                    var_dump($ret);
                    echo json_encode('http://'.$domian.$file);

                }
            }
        }
    }

}