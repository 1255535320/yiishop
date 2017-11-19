<?php

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'goods_category';
    }

    public function behaviors()
    {
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

//准备获取ztree数据
    public static function getZtree()
    {
        return self::find()->select(['id', 'name', 'parent_id'])->asArray()->all();
//    {id:1, pId:0, name: "父节点1"},
//    {id:11, pId:1, name: "子节点1"},
//    {id:12, pId:1, name: "子节点2"})
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'parent_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
//            'tree' => 'Tree',
//            'lft' => 'Lft',
//            'rgt' => 'Rgt',
//            'depth' => 'Depth',
            'name' => '分类名',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }

    //首页显示商品分类
    public static function getIndexGoodsCategory()
    {
        //redis优化
        //缓存使用----优先读取缓存,没有就查询生成缓存
        $redis = new \Redis();
        $redis->connect('127.0.0.1');//链接
        $html= $redis->get('goods-category');
        //判断是否有缓存
        if (!$html){
            $html = '<div class="cat_bd">';
            //遍历一级分类
            $categories = self::find()->where(['parent_id' => 0])->all();
            foreach ($categories as $k1=>$category) {
                $html .= '<div class="cat '.($k1==0?'item1':'').'">
                    <h3><a href="'.Url::to(['/member/list','id'=>$category->id]).'">' . $category->name . '</a><b></b></h3>
                    <div class="cat_detail">';
                //遍历二级分类
                $categories2 = $category->children(1)->all();
                foreach ($categories2 as $k2=>$category2) {
                    $html .= '<dl '.($k2==0?'dl_1st':'').'>
                            <dt><a href="'.Url::to(['/member/list','id'=>$category2->id]).'">' . $category2->name . '</a></dt>
                            <dd>';
                    //遍历三级分类
                    $categories3=$category2->children(1)->all();
                    foreach ($categories3 as $category3){
                        $html .= '<a href="'.Url::to(["/member/list","id"=>$category3->id]).'">'.$category3->name.'</a>';
                    }
                    $html .= '</dd>
                     </dl>';
                }
                $html .= ' </div>
                </div>';
            }
            $html .= '</div>';
//            保存redis
            $redis->set('goods-category',$html,24*3600);
        }

        return $html;
    }
}
