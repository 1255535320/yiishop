<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12
 * Time: 11:55
 */

namespace frontend\controllers;


use Codeception\Module\Redis;
use frontend\components\Sms;
use frontend\models\LoginForm;
use frontend\models\Vip;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller
{
    //登陆
    public function actionLogin()
    {
        //YII自带的表单模型
        $model = new LoginForm();
        $request = new Request();
        if ($request->isPost) {
            //接收数据
            //非表单组件生成的需要第二个参数,默认为User,改成'';
            $model->load($request->post(), '');
            //验证
            if ($model->validate()) {
                if ($model->login()) {
                    //提示并且跳转
                    \Yii::$app->session->setFlash('success', '登陆成功');
                    return $this->redirect('index');
                } else {
                   return var_dump($model->getErrors()
                    );
                }
            };
        }
        //显示登陆表单
        return $this->render('login');
    }

    //列表
    public function actionIndex()
    {
        return $this->render('index');
    }

    //注册用户
    public function actionVipAdd()
    {
        $request = new Request();
        $model = new Vip();
        if ($request->isPost) {
            //接收数据
            //var_dump($request->post());exit;
            $model->load($request->post(), '');
//            var_dump($model);exit;
            if ($model->validate()) {
//                var_dump($model);exit;
                //密码加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
//                var_dump($model->tel);exit;
                $model->save(false);
//                var_dump($model);exit;
                //提示和跳转
                \Yii::$app->session->setFlash('warning', '注册成功');
                return $this->redirect('login');
            }

        }
        //展示注册表单
        return $this->render('regist');
    }

    //ajax发送短信
    public function actionAjaxsms($phone){
//        var_dump(56546);exit;
        //接收请求的手机号
        $code=rand(1000, 9999);
        //1.发送随机验证码短信
        $response = Sms::sendSms(
            "佳荟萃", // 短信签名
            "SMS_109460462", // 短信模板编号
            $phone,// 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
            )
        );
//        var_dump($response);exit;
        //2.根据$response判断是否发送成功
        if ($response->Code=='OK'){
            //3.保存验证码redis
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('captcha_'.$phone,$code,5*60);//保存5分钟
            //验证验证码
//            $code=$redis->get('captcha_'.$phone);
            return 'success';
        }else{
            return 'false';
        }



    }
    //ajax验证手机短信
    public function actionCheckmsn(){
        //从redis获取验证码
        $redis = new \Redis();
        $request=new Request();
        //接收数据
        var_dump($request->post());exit;
        $phone1=$request->post()->tel;
        $captcha=$request->get()->captcha;
        if($redis->exists('captcha_'.$phone1 )){
            //验证code
            if($redis->get('captcha_'.$phone1)==$captcha){
                return true;
            }else{
                return false;
            }
        }

        //进行对比

    }
    //验证用户信息唯一性
    public function actionCheckname($username)
    {
//        echo 111;exit();
        $model = Vip::findOne(['username' => $username]);
        //验证用户名
        if ($model) {
            return 'false';
        } else {
            return 'true';

        }
    }

    //验证邮箱
    public function actionCheckemail($email)
    {
//        echo 111;exit();
        $model = Vip::findOne(['email' => $email]);
        //验证用户名
        if ($model) {
            return 'false';
        } else {
            return 'true';

        }
    }

    //验证手机号
    public function actionChecktel($tel)
    {
//        echo 111;exit();
        $model = Vip::findOne(['phone' => $tel]);
        //验证用户名
        if ($model) {
            return 'false';
        } else {
            return 'true';

        }
    }

    //验证短信
//    public function actionSms()
//    {
//        $response = Sms::sendSms(
//            "佳荟萃", // 短信签名
//            "SMS_109460462", // 短信模板编号
//            "18011310026", // 短信接收者
//            Array(  // 短信模板中字段的值
//                "code" => rand(1000, 9999),
////                "product"=>"dsd"
//            )
////            "123"   // 流水号,选填
//        );
//        echo "发送短信(sendSms)接口返回的结果:\n";
//        print_r($response);
//
//    }
}