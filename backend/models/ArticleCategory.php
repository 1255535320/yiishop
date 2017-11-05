<?php

namespace backend\models;
use backend\models\Article;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro','sort','name','status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'name' => '分类名',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
    //查询文章分类
    public static function getItems(){
       return ArrayHelper::map(self::find()->asArray()->all(),'id','name');
    }
    //根据分类id查询文章    1对多
    public function getArticle(){
        return $this->hasOne(Article::className(),['article_category_id'=>'id']);
    }

}
