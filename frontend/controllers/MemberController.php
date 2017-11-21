<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12
 * Time: 11:55
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use Codeception\Module\Redis;
use frontend\components\Sms;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Vip;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;
use Yii;

class MemberController extends Controller
{
    public $enableCsrfValidation=false;
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
        $request = \Yii::$app->request;
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
    public function actionAjaxsms($tel)
    {
//        var_dump(56546);exit;
        //接收请求的手机号
        $code = rand(1000, 9999);
        //1.发送随机验证码短信
        $response = Sms::sendSms(
            "佳荟萃", // 短信签名
            "SMS_109460462", // 短信模板编号
            $tel,// 短信接收者
            Array(  // 短信模板中字段的值
                "code" => $code,
            )
        );
//        var_dump($response);exit;
        //2.根据$response判断是否发送成功
        if ($response->Code == 'OK') {
            //3.保存验证码redis
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('captcha_' . $tel, $code, 5 * 60);//保存5分钟
            //验证验证码
//            $code=$redis->get('captcha_'.$phone);
            return 'success';
        } else {
            return 'false';
        }


    }

    //ajax验证手机短信
    public function actionCheckmsn()
    {
        //从redis获取验证码
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $request = new Request();
        //接收数据
//        var_dump($request->get());exit;
        $phone1 = $request->get("tel");
        $captcha = $request->get("captcha");
        if ($redis->exists('captcha_' . $phone1)) {
            //验证code
            if ($redis->get('captcha_' . $phone1) == $captcha) {
                return 'true';
            } else {
                return 'false';
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
        $model = Vip::findOne(['tel' => $tel]);
        //验证用户名
        if ($model) {
            return 'false';
        } else {
            return 'true';

        }
    }

    public function actionList($id)
    {
        //商品分类
        $goods_category = GoodsCategory::findOne(['id' => $id]);
        //三级分类
        if ($goods_category->depth == 2) {
            $query = Goods::find()->where(['id' => $id]);

        } else {//二级分类
            $ids = $goods_category->children()->andwhere(['depth' => 2])->column();
            $query = Goods::find()->where(['in', 'id', $ids]);
        }
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->pageSize = 20;
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
//        var_dump($ids);exit;
        return $this->render('list', ['models' => $models, 'pager' => $pager]);
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
    //商品详情
    public function actionGoods($id)
    {
        $model = Goods::findOne($id);
        $img = GoodsGallery::find()->where(['goods_id' => $id])->all();
//        var_dump($img);exit;
        return $this->render('goods', ['model' => $model, 'img' => $img]);
    }

    //加入购物车
    public function actionAddCart($goods_id, $amount)
    {
//        var_dump($id,$amount);exit;
        //判断用户是否登录
        if (\Yii::$app->user->isGuest) {
            //游客,购物车存入cookie
            $cookies = \Yii::$app->request->cookies;//可读的cookie
            $carts = $cookies->getValue('carts');//读取购物车
            if ($carts) {
                $carts = unserialize($carts);//如果购物车还有其他商品,则合并在一起
            } else {
                $carts = [];
            }
            //判断购物车中是否有该商品,有就叠加数量,没有直接添加
            if (array_key_exists($goods_id, $carts)) {
                $carts[$goods_id] += $amount;
            } else {
                //直接添加
                $carts[$goods_id] = $amount;
            }
            //将购物车保存在cookie中
            $cookies = \Yii::$app->response->cookies; //可写的cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);
        } else {//已登录,
            $request = new Request();
            $model = new Cart();
            $model->load($request->get(), '');
            $carts = Cart::findOne(['member_id' => \Yii::$app->user->getId(), 'goods_id' => $goods_id]);
            if ($carts) {
                //数据库有该商品
                $carts->amount = $carts->amount + $amount;
                $carts->save();
//                return $this->redirect('cart');
            } else {
                $model->member_id = \Yii::$app->user->getId();
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->save();
//                return $this->redirect('cart');
            }
        }
        return $this->redirect('cart');
    }

    //购物车页面
    public function actionCart()
    {
        //判断是否登录
        if (\Yii::$app->user->isGuest) {
            //未登录--从cookie中取出数据显示在事业
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if ($carts) {
                $carts = unserialize($carts);//存在取出
            } else {
                $carts = [];
            }
            //获取购物车商品信息
            $models = Goods::find()->where(['in', 'id', array_keys($carts)])->all();
//            var_dump($models);exit;
//            var_dump(111);exit;
        } else {
            //已登录
            //获取购物车信息
            $carts1 = Cart::find()->where(['member_id' => \Yii::$app->user->getId()])->all();
            foreach ($carts1 as $cart) {
                $cart2[] = $cart->goods_id; //遍历商品id
                $carts[$cart->goods_id] = $cart->amount; //将购物车的商品id和数量联系起来
            }
            $models = Goods::find()->where(['in', 'id', $cart2])->all();
//
        }//展示视图
     //总计金额
        $count = 0;
        return $this->render('cart', ['carts' => $carts, 'models' => $models, 'count' => $count]);
    }

    //删除
    public function actionCartDel($goods_id)
    {
//        var_dump($amount);exit;
        Cart::findOne(['goods_id' => $goods_id])->delete();
        return $this->redirect('cart');
    }

    //ajax更新购物车
    public function actionAjaxCart($type)
    {
        //登陆操作数据库,未登录操作cookie
        switch ($type) {
            case 'change';//修改购物车
                $goods_id = \Yii::$app->request->get('goods_id');
                $amount = \Yii::$app->request->get('amount');
//                var_dump($goods_id,$amount);exit;
                //判断是否登陆
                if (\Yii::$app->user->isGuest){
                    //取出cookie中的购物车
                    $cookies=\Yii::$app->request->cookies;
                    $carts=$cookies->getValue('carts');
                    if ($carts){//判断购物车中是否有商品
                        $carts = unserialize($carts);//$carts = ['1'=>'3','2'=>'2'];
                    }else{
                        $carts=[];
                    }
                    //修改购物车商品数量
                    $carts[$goods_id]=$amount;
                    //保存cookie
                    $cookies=\Yii::$app->response->cookies;
                    $cookie=new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value=serialize($carts);
                    $cookies->add($cookie);
                }else{
                    //已登陆状态
                    $carts=Cart::findOne(['goods_id'=>$goods_id]);
//                    var_dump($carts);exit;
                    $carts->amount=$amount;
                    $carts->save();
                }
        }
    }
    //订单功能
    public function actionOrder(){
        $sd=[];
        //判断用户是否登陆
        if (\Yii::$app->user->isGuest){
            //跳转到登陆页面
            return $this->redirect('login');
        }else{
            $request=\Yii::$app->request;
            if ($request->isPost){
                //var_dump($request->post());exit;
                $order=new Order();
                $order->member_id=\Yii::$app->user->id;
                //根据地址id获取地址电话信息
                $address_id=$request->post('address_id');
                $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->id]);
                $order->name=$address->name;
                $order->province=$address->province;
                $order->city=$address->city;
                $order->area=$address->area;
                $order->address=$address->address;
                $order->tel=$address->phone;
//                //配送方式价格
                $delivery_id = $request->post('delivery_id');//
                $order->delivery_name = Order::$delivery[$delivery_id][0];
                $order->delivery_price = Order::$delivery[$delivery_id][1];
//                //支付方式
                $order->payment_name= Order::$payment[$request->post('pay_id')];
//                //时间.金额,状态
                $order->create_time=time();
                $order->status=1;
                $order->total=0;
                //计算价格
                $carts1=Cart::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();
                //获取购物车信息
                foreach ($carts1 as $cart) {
                    $goods = Goods::findOne([["id"=>$cart->goods_id]]);
                    $order->total += $goods->shop_price*$cart->amount;
                }
                $order->total += $order->delivery_price;//将购物车的商品id和数量联系起来
//                $order->save();
                //开启事务
                $transaction=Yii::$app->db->beginTransaction();
                try{
                    if ($order->save()){
                        //订单保存到订单商品表
                        $carts=Cart::find()->where(['member_id'=>Yii::$app->user->id])->all();
                        foreach ($carts as $cart){
                            //检测库存是否足够
//                            if($cart->amount > $cart->goods->stock){
//                                throw new Exception($cart->goods->name.'库存不足');
//                            }
                            $order_goods=new OrderGoods();
                            $order_goods->order_id=$order->id;
                            $order_goods->goods_id=$cart->goods_id;
                            $order_goods->goods_name=$cart->goods->name;
                            $order_goods->logo=$cart->goods->logo;
                            $order_goods->price=$cart->goods->shop_price;
                            $order_goods->amount=$cart->amount;
                            $order_goods->total=$order_goods->price*$order_goods->amount;
                            $order_goods->save();
                            //扣除商品库存
                            Goods::updateAllCounters(['stock'=>-$cart->amount],['id'=>$cart->goods_id]);

                        }//删除购物车
                        Cart::deleteAll('member_id='.Yii::$app->user->id);
                        $order->save();

                    }
                    //提交事务
                    $transaction->commit();
                    $sd=1;
//                    return $this->redirect('success');
                }catch (Exception $e){
                    //回滚
                    $transaction->rollBack();
                    $sd=0;
                    //提示库存不足,返回购物车
                    echo $e->getMessage();exit;
                }
                  echo json_encode($sd);exit;

            }//展示订单表单
            //查询地址
            $address=Address::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();
            //查询订单
            $carts1=Cart::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();
            //获取购物车信息
            foreach ($carts1 as $cart) {
                $cart2[] = $cart->goods_id; //遍历商品id
                $carts[$cart->goods_id] = $cart->amount; //将购物车的商品id和数量联系起来

            }
            $models = Goods::find()->where(['in', 'id', $cart2])->all();
            $count = 0;$amounts=0;
            return $this->render('flow2',['address'=>$address,'models'=>$models,'carts'=>$carts,'count'=>$count,'amounts'=>$amounts]);
        }
    }

    //支付成功页面
    public function actionSuccess(){
        return $this->render('flow3');
    }
    //ajax获取用户登录状态
    public function actionHead(){
        $isLogin=!Yii::$app->user->isGuest;
        $username=$isLogin?Yii::$app->user->identity->username:'';
        return json_encode(['isLogin'=>$isLogin,'username'=>$username]);
    }

}