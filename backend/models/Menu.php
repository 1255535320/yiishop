<?php

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $top_menu
 * @property string $address
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['name', 'top_menu', 'address'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
            'name' => '菜单名',
            'top_menu' => '选择上级菜单',
            'address' => '路由',
            'sort' => '排序',
        ];
    }
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public static function getAllMenu(){
        $menus = self::find()->all();
        $arr = [0=>"顶级菜单"];
        return ArrayHelper::merge($arr,ArrayHelper::map($menus,'id','name'));
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsQuery(get_called_class());
    }
    //一级菜单和二级菜单的关系  1对多
    public function getChildren(){
        return $this->hasMany(self::className(),(['top_menu'=>'id']));
    }

}
