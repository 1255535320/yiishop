<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgfile;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro','sort','status','name','logo'], 'required'],
//            ['logo','safe'],
//            ['imgfile','file','skipOnEmpty'=>false,'extensions'=>['jpg','png','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '品牌名',
            'intro' => '简介',
            'imgfile' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
    //查询品牌分类
    public static function getItems(){
        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');

    }

}
