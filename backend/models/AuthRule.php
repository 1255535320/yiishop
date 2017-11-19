<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_rule".
 *
 * @property string $name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    public $name;
    public $description;
    public $permissions;
    public $oldName;

    /**
     * @inheritdoc
     */
    //场景
    const SCENARIO_Add = 'add';
    const SCENARIO_EDIT = 'edit';

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['permissions'], 'safe'],
            //自定义验证规则
            ['name','validateName','on'=>[self::SCENARIO_Add]],//添加时生效 修改时不生效
            ['name','validateUpdateName','on'=>self::SCENARIO_EDIT],//修改时验证
        ];
    }
    //自定义验证方法,仅失败时候生效
    public function validateName(){
        $auth = \Yii::$app->authManager;
        $model = $auth->getRole($this->name);
        if($model){
            //权限已存在
            $this->addError('name','角色已存在');
        }
    }
    //修改时验证权限名
    public function validateUpdateName()
    {
        //只处理验证失败的情况  名称被修改,新名称已存在
        $auth = \Yii::$app->authManager;
        if ($this->oldName != $this->name) {
            $model = $auth->getRole($this->name);
            if ($model) {
                //权限已存在
                $this->addError('name', '权限已存在');
                //return false;
            }
        }
    }
    //更新权限
    public function update($name){
        $auth = \Yii::$app->authManager;
        $permission = $auth->getRole($name);
        $permission->name = $this->name;
        $permission->description = $this->description;
        return $auth->update($name,$permission);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '角色名',
//            'data' => 'Data',
            'description' => '描述',
            'permissions' => '权限',
//            'created_at' => 'Created At',
//            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getAuthItems()
//    {
//        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
//    }
}
