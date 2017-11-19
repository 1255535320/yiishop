<?php

namespace backend\controllers;

//商品表

use backend\models\Article;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSee;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    //商品列表
    public function actionList()
    {
        $query = Goods::find();
        //分页
        $request = new Request();
        $see = new GoodsSee();
        $sees = $request->get("GoodsSee");// ==$sees['GoodSee'];
        $name = $sees["name"] ? $sees["name"] : "";
//        $sn=$sees["name"]?$sees["sn"]:"";
        //var_dump($sees['name']);exit;

//        $query = Goods::find()->where(['name'=>$request->post()->name]);
        $pager = new Pagination();//实例化分页工具模型
        //获取总页数
        $pager->totalCount = $query->count();
        $pager->pageSize = 3;
        //查询数据
        $models = $query->limit($pager->limit)->offset($pager->offset)->andwhere([
            'or',
            ['like', 'name', $name],
//            ['like','name',$sn],
        ])->all();
        return $this->render('index', [
            'models' => $models,
            'pager' => $pager,
            'see' => $see,
        ]);
    }

    //商品添加
    public function actionAdd()
    {
        $request = new Request();
        $intro = new GoodsIntro();//详情
        $model = new Goods();//简介
        //实例化商品分类模型
        $categroy = new GoodsCategory();

        //给goods_category_id设置默认值,避免报错
        $model->goods_category_id = 0;
        if ($request->isPost) {
            $model->load($request->post());
            $intro->load($request->post());
            //var_dump($model);exit;
            if ($model->validate() && $intro->validate()) {
                $goodsday = new GoodsDayCount();
                $day = date('Y-m-d', time());
                //获取当前计数
                if ($goodsday->findOne(["id" => $day])) {
                    $count = $goodsday->findOne(["id" => $day])->count + 1;

                } else {
                    $goodsday->id = date('Y-m-d', time());
                    $goodsday->count = 0;
                    $goodsday->save();
                    $count = 1;
                }
//        var_dump($goodsday),exit()
                //拼接数量固定长度-不足补0
                $count = sprintf('%04s', $count); //四位数拼接,不足自动左边补0
                $model->sn = date('Ymd', time()) . $count;
                $model->save();
                $intro->goods_id = $model->id;
                $intro->save();
                $goods = GoodsDayCount::findOne(["id" => $day]);
                $goods->count = $count;
                $goods->save();
                //提示并跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list');
            }

        }
        return $this->render('add', [
            'model' => $model,
            'intro' => $intro,
            'categroy' => $categroy,

        ]);
    }

    //商品删除
    public function actionDelete($id)
    {
        $result = Goods::findOne($id);
        $result1 = GoodsIntro::findOne($id);
//        $result2=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $act = [];
        //判断是否删除成功
        if ($result && $result1) {
            $result->delete();
            $result1->delete();
//            $result2->delete();
            $act = 1;
        }
        echo json_encode($act);
    }

//修改商品
    public function actionUpdate($id)
    {
        $model = Goods::findOne($id);
        $intro = GoodsIntro::findOne($id);
        $request = new Request();
        //判断请求方式
        if ($request->isPost) {
            $model->load($request->post());
            $intro->load($request->post());
            //var_dump($model);exit;
            if ($model->validate() && $intro->validate()) {
                $model->save();
                $intro->goods_id = $model->id;
                $intro->save();
                //提示并跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect('list');
            }
        }
        return $this->render('add', [
            'model' => $model,
            'intro' => $intro,
        ]);
    }

    //富文本
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }

    //处理ajax图片上传
    public function actionAjax()
    {
        if (\Yii::$app->request->isPost) {
            $imgfile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($imgfile) {
                $file = '/upload/' . uniqid() . '.' . $imgfile->extension;
                $imgfile->saveAs(\Yii::getAlias('@webroot') . $file, 0);
                return json_encode($file);
            }
        }
    }

    //相册
    public function actionPhoto($id)
    {
        //$goods=Goods::findOne($id);
        $request=new Request();
        $model=new GoodsGallery();
        if ($request->isPost) {
            $model->load($request->post());
            $photo = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($photo) {
                $file = '/photo/' . uniqid() . '.' . $photo->extension;
                $photo->saveAs(\Yii::getAlias('@webroot') . $file, 0);
                //根据商品ID存放图片
                $model->goods_id=$id;
                //给path图片地址
                $model->path=$file;
                $model->save();
                return json_encode($file);
            }
        }
        $models=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //var_dump($models);exit();
        return $this->render('addphoto',[
            'model'=>$model,
            'models'=>$models,

            ]);
    }
    //删除相册图片
    public function actionImgdelete($id){
        $model=GoodsGallery::findOne($id);
        if($model){
            $model->delete();
            //提示并跳转
            \Yii::$app->session->setFlash('success', '删除成功');
            return $this->redirect('list');
        }

    }
    //前台商品列表
//    public function actionIndex($id)
//    {
//        //商品分类
//        $goods_category = GoodsCategory::findOne(['id' => $id]);
//        //三级分类
//        if ($goods_category->depth == 2) {
//            $query = Goods::find()->where(['id' => $id]);
//
//        } else {//二级分类
//            $ids = $goods_category->children()->andwhere(['depth' => 2])->column();
//            $query = Goods::find()->where(['in', 'id', $ids]);
//        }
//        $pager = new Pagination();
//        $pager->totalCount = $query->count();
//        $pager->pageSize = 20;
//        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
////        var_dump($ids);exit;
//        return $this->render('index', ['models' => $models, 'pager' => $pager]);
//    }

}

