<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:15
 */

namespace backend\controllers;

//文章控制器

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //文章添加
    public function actionAdd(){
        $request = new Request();
        //文章简介模型
        $art=new Article();
        //文章内容模型
        $article= new ArticleDetail();
        if ($request->isPost){
            //接收数据
            $art->load($request->post());
            $article->load($request->post());
            if ($art->validate() && $article->validate()){
//                echo 1111;exit();
                $art->save();
                $article->article_id=$art->id;
                $article->save();
                //提示并跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list');
            }

        }
        return $this->render('add',[
            'art'=>$art,
            'article'=>$article
        ]);

    }
    //文章列表
    public function actionList(){
        $query= Article::find();
        $pager = new Pagination();
        //获取总页数
        $pager->totalCount=$query->count();
        $pager->pageSize=3;
        $art = $query->limit($pager->limit)->offset($pager->offset)->all();


//        $article= ArticleDetail::find()->all();
        return $this->render('index',[
            'arts'=>$art,
            'pager'=>$pager
        ]);
    }
    //修改文章
    public function actionUpdate($id){

            $request = new Request();
            //文章简介模型
            $art=Article::findOne(["id"=>$id]);
//            $article_id=$id;
            //文章内容模型
            $article=ArticleDetail::findOne(["article_id"=>$id]);
//            var_dump($id,$art,$article);exit;
            if ($request->isPost){
                //接收数据
                $art->load($request->post());
//                $article->load($request->post());
                if ($art->validate() && $article->validate()){
//                echo 1111;exit();
                    $art->save();
                    $article->article_id=$art->id;
                    $article->save();
                    //提示并跳转
                    \Yii::$app->session->setFlash('success', '修改成功');
                    return $this->redirect('list');
                }

            }
            return $this->render('add',[
                'art'=>$art,
                'article'=>$article
            ]);

        }
        //删除
    public function actionDelete($id){
        $art = Article::findOne(['id'=>$id]);
        $act=[];
        if ($art){
            $art->delete();
           $act=1;
        }
        echo json_encode($act);
    }

}