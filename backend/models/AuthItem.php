<?php

namespace backend\models;


use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

//权限添加模型
/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $permissions;
    public static function tableName()
    {
        return 'auth_item';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','description'], 'required'],
            [['name'], 'unique'],
            [["permissions"],"safe"]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称(路由)',
            'description' => '描述',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public static function getItems(){
        $permissions= \Yii::$app->authManager->getPermissions();
        return ArrayHelper::map($permissions,'name','name');
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getAuthAssignments()
//    {
//        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getRuleName()
//    {
//        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getAuthItemChildren()
//    {
//        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getAuthItemChildren0()
//    {
//        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getChildren()
//    {
//        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getParents()
//    {
//        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
//    }
}
