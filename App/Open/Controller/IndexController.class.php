<?php
namespace Open\Controller;

use Open\Model\OpenclassModel;
use Think\Controller;
use Think\Exception;

class IndexController extends Controller
{
    public function index ()
    {
        try{
            !(IS_POST && IS_AJAX) || E('非法请求');
            $params                 =   I('get.');
            $conditions             =   $this->conditionsHandle($params);
            $model                  =   new OpenclassModel();
            $lists                  =   $model->lists($conditions['conditions'],$conditions['order'],$conditions['limit']);
            $result                 =   true;
            $_sql                   =   $model->_sql();
            $count                  =   (int)$model->where($conditions['conditions'])->count();

            $this->ajaxReturn( compact('result', 'conditions', 'lists', 'count', '_sql') );
        }catch (Exception $e){
            $this->ajaxReturn([
                'result'        =>  true,
                'error'         =>  $e->getMessage(),
            ]);
        }
    }

    public function conditionsHandle ($params)
    {
        $conditions             =   [];
        $order                  =   'id desc';
        $livecate               =   'livecate';
        $livecontent            =   'livecontent';
        $search                 =   'search';
        $is_rec                 =   'is_reco';
        $page_key               =   'page';
        $limit_key              =   'limit';
        $order_key              =   'order';
        // 直播类型
        if( key_exists($livecate,$params) )
            $conditions[$livecate]      =   ['eq',(int)$params[$livecate]];
        // 是否推荐
        if( key_exists($is_rec, $params) )
            $conditions[$is_rec]        =   ['eq',$params[$is_rec]];
        // 名称
        if( key_exists($search, $params) )
            $conditions['names']        =   ['LIKE',"%{$params[$search]}%"];
        // 内容类型
        if( key_exists($livecontent, $params) ){
            $content_types              =   [];
            foreach ($params[$livecontent] as $content){
                $content_types[$content]        =  "FIND_IN_SET({$content},{$livecontent})";
            }
            $conditions['_string']      =   implode( ' AND ', $content_types );
        }
        // 排序
        if( key_exists($order_key, $params) )
            $order                      =   $params[$order_key];
        // 页数
        $page                           =   key_exists($page_key, $params)
            ?   (int)$params[$page_key]
            :   1;
        // 条目数  最多一次显示100条
        $limit                          =   !key_exists($limit_key, $params)
            ?   20
            :   ($params[$limit_key]>100 ? 100 : (int)$params[$limit_key]);

        return ['conditions'=>$conditions, 'order'=>$order, 'limit'=>compact('page','limit') ];
    }

    public function detail ()
    {
        try{
            !(IS_POST && IS_AJAX) || E('非法请求');
            $id             =   I('get.id',false,'int');
            ($id===false) && E('参数缺失');
            $model          =   new OpenclassModel();
            $info           =   $model->relation(true)
                ->field(true)
                ->find($id);
            $info || E('数据不存在');
            if( !session('?_student') ){
                $info['playback_addr']      =   'NEED_AUTH';
            }
            $this->ajaxReturn( ['result'=>true,'info'=>$info] );
        }catch (Exception $e){
            $this->ajaxReturn([
                'result'        =>  false,
                'error'         =>  $e->getMessage(),
            ]);
        }
    }
}