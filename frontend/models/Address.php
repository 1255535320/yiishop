<?php

namespace frontend\models;

use frontend\controllers\MemberController;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property integer $member_id
 * @property string $phone
 * @property string $address
 * @property integer $create_at
 */
class Address extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'address','province','city','area'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'member_id' => '会员名',
            'phone' => '电话',
            'address' => '收货地址',
            'province' => '省份',
            'city' => '城市',
            'area' => '区县',
//            'create_at' => '创建订单时间',
        ];
    }
    //根据用户id查询地址
    public static function getAddress(){
        return self::hasOne(Vip::className(),['id'=>'member_id']);
    }
}
