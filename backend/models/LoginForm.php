<?php

namespace backend\models;

use Yii;
use yii\base\Model;

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
class LoginForm extends Model
{

    public $username;
    public $password_hash;
    public $remember;
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
            [['username', 'password_hash'],'required'],
            [['remember'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password_hash' => '密码',
            'remember'=>'记住登陆',

        ];
    }
    //验证登陆的方法
    public function login(){
        //验证账号
        $user=User::findOne(['username'=>$this->username]);
        if ($user){
            //调用安全组件中的方法验证密码
            if (\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                //验证通过,保存登陆信息
                if ($this->remember){//记住登陆
                    \Yii::$app->user->login($user,3600);
                }else{
                    \Yii::$app->user->login($user);
                }
                return true;
            }else{
                //密码验证失败
                $this->addError('password_hash','密码错误');
            }

        }else{
            //提示错误
            $this->addError('username','账号不存在');
        }
        return false;
    }
}
