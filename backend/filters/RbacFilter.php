<?php

namespace backend\filters;

use yii\base\ActionFilter;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        //用路由作为权限名
        return \Yii::$app->user->can($action->uniqueId);//$action->uniqueId  当前路由
    }
}