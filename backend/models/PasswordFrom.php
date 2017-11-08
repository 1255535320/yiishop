<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:29
 */

namespace backend\models;


use yii\base\Model;

class PasswordFrom extends Model
{
    public $oldpassword;
    public $newpassword;
    public $repassword;

    public function attributeLabels()
    {
        return [
            'oldpassword' => '旧密码',
            'newpassword' => '新密码',
            'repassword' => '确认密码',
        ];
    }
    public function rules()
    {
        return [
            [['oldpassword','newpassword','repassword'],'required'],
            //两次密码一致
            ['repassword','compare','compareAttribute'=>'newpassword','message'=>'两次密码不一致'],
        ];
    }
}