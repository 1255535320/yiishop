<?php
namespace backend\models;
use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 0:11
 */
class GoodsQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}