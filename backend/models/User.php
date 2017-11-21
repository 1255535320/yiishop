<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\validators\IpValidator;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_login_time
 * @property string $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $roles;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'status'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            //唯一性验证
            [['username'], 'unique'],
//            [['username'],'unique'],
//            [['status', 'created_at', 'updated_at'], 'integer'],
//            [['username', 'password_hash', 'password_reset_token', 'email', 'last_login_time', 'last_login_ip'], 'string', 'max' => 255],
//            [['auth_key'], 'string', 'max' => 32],
//            [['username'], 'unique'],
//            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
            'username' => '用户名',
//            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
//            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'roles'=>'角色',

//            'created_at' => 'Created At',
//            'updated_at' => 'Updated At',
//            'last_login_time' => 'Last Login Time',
//            'last_login_ip' => 'Last Login Ip',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at', 'last_login_time',],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at', 'last_login_time'],
                ],
            ],
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    //获取用户对应菜单
    public function getMenus()
    {//登陆后的菜单
        /*$menuItems = [
                    ['label'=>'下拉菜单','items'=>[
                        ['label'=>'添加分类','url'=>['goods/add-category']],
                        ['label'=>'分类列表','url'=>['goods/ztree']],
                    ]],
                ];
        */
        //完整导航菜单组
        $menuItems = [];
        //获取1级菜单--即父id=0;
        $menus = Menu::find()->where(['top_menu' => 0])->all();
        //遍历一级菜单
        foreach ($menus as $menu) {
            //存放二级菜单
            $items = [];
            //遍历二级菜单
            foreach ($menu->children as $child) {
                //判断用户权限
                if (Yii::$app->user->can($child->address)) {
                    $items[] = ['label' => $child->name, 'url' => [$child->address]];
                };
            }

            $menuItem = ['label' => $menu->name, 'items' => $items];
            //给菜单组加一级菜单
            //判断顶级菜单是否有子菜单
//            if ($items){
                $menuItems[] = $menuItem;
//            }
        }
        return $menuItems;
    }

}
