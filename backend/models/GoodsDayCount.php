<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_day_count".
 *
 * @property string $id
 * @property integer $count
 */
class GoodsDayCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_day_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id'], 'required'],
            [['id'], 'safe'],
            [['count'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '日期',
            'count' => '每日商品数',
        ];
    }
}
