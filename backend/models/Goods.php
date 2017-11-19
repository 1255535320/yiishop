<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
//    public function transactions()
//    {
//        return [
//            self::SCENARIO_DEFAULT => self::OP_ALL,
//        ];
//    }
//    public $imgfile;

//    public function behaviors(){
//        return [
//            'tree' => [
//                'class' => NestedSetsBehavior::className(),
//                'treeAttribute' => 'tree',
//                // 'leftAttribute' => 'lft',
//                // 'rightAttribute' => 'rgt',
//                // 'depthAttribute' => 'depth',
//            ],
//        ];
//    }

    public static function find()
    {
        return new GoodsQuery(get_called_class());
    }

    public static function getZtree(){
        return self::find()->select(['id','name','parent_id'])->asArray()->all();
    }

    public function rules()
    {
        return [
            [['goods_category_id','name','brand_id','stock','logo', 'is_on_sale','sort'], 'required'],
            [['market_price', 'shop_price','status'], 'number'],
//            [['sn'],'safe'],
//            ['imgfile','file','skipOnEmpty'=>false,'extensions'=>['jpg','png','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
//            'create_time' => '添加时间',
//            'view_times' => '浏览次数',
        ];
    }
    //查询品牌分类
    public static function getCategory(){
        return self::hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //
}
