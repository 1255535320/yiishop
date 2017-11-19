<?php

namespace backend\models;

use Yii;
use yii\base\Model;

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
class GoodsSee extends Model
{
    /**
     * @inheritdoc
     */
    public $name;
//    public $sn;
//    public $;
//    public $select;
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['name'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
         'name'=>'商品名'
        ];
    }
}
