<?php

namespace frontend\models;

use backend\models\Goods;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property string $delivery_name
 * @property double $delivery_price
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $delivery=[
      1=>['普通快递',10] ,
      2=>['申通',12],
      3=>['顺丰',22],
    ];
    //支付方式
    public static $payment=[
        1=>'在线支付',
        2=>'货到付款',
        ];
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
//    public function rules()
//    {
//        return [
//            [['member_id', 'name', 'province', 'city', 'area', 'address', 'tel', 'delivery_name', 'delivery_price', 'payment_name', 'total'], 'required'],
//            [['delivery_price', 'total'], 'number'],
//            [[ 'create_time'], 'integer'],
//            [['member_id', 'name', 'province', 'city', 'area', 'address', 'delivery_name', 'payment_name'], 'string', 'max' => 255],
//            [['tel'], 'string', 'max' => 11],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'tel' => 'Tel',
            'delivery_name' => '配送方式',
            'delivery_price' => '运费',
            'payment_name' => '在线支付',
            'total' => '订单金额',
//            'status' => '订单状态（0已取消1待付款2待发货3待收货4完成）',
//            'trade_no' => '第三方支付交易号',
            'create_time' => '第三方支付交易号',
        ];
    }

}
