<?php
namespace Open\Controller;

use Common\Controller\BaseController;
use Open\Model\LivecateModel;
use Open\Model\LivecontentModel;
use Open\Model\OpenclassModel;
use Think\Controller;
use Think\Exception;

class IndexController extends Controller
{
    public function index ()
    {
        try{
            (IS_POST && IS_AJAX) || E('非法请求');
            $params                 =   I('get.');
            $conditions             =   $this->conditionsHandle($params);
            $model                  =   new OpenclassModel();
            $lists                  =   $model->lists($conditions['conditions'],$conditions['order'],$conditions['limit']);
            $result                 =   true;
            $_sql                   =   $model->_sql();
            $count                  =   $model->countNum($conditions['conditions']);
            $crm_domain             =   C('CRM_DOMAIN');

            $_init                  =   [];
            if($conditions['init']){
                $livecateModel          =   new LivecateModel();
                $livecontentModel       =   new LivecontentModel();
                $_init['livecate']      =   $livecateModel->field('id,cate_name')->select();
                $_init['livecontent']   =   $livecontentModel->field('id,cate_name')->select();
            }
            $remark                 =   [
                '数据返回'      =>  'params:参数;lists:数据集;count:总数量;crm_domain:资源域名;_init:初始化数据',
                '查询条件'      =>  'page:页码;limit:每页显示数量;livecate:直播分类;livecontent:直播内容(复合);is_reco:是否推荐;search:名称搜索;init:是否需要返回初始值(0.false,1.true)',
            ];

            $this->ajaxReturn( compact('result', '_init', 'params', 'lists', 'count', 'crm_domain', 'remark') );
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
        $init_key               =   'init';
        // 直播类型
        if( key_exists($livecate,$params) && $params[$livecate] )
            $conditions[$livecate]      =   ['eq',(int)$params[$livecate]];
        // 是否推荐
        if( key_exists($is_rec, $params) )
            $conditions[$is_rec]        =   ['eq',(int)$params[$is_rec]];
        // 名称
        if( key_exists($search, $params) && $params[$search] )
            $conditions['names']        =   ['LIKE',"%{$params[$search]}%"];
        // 内容类型
        if( key_exists($livecontent, $params) && ($content_types = array_map( 'intval', array_filter( $params[$livecontent]) ?: [] )) ){
            $conditions['lc.id']        =   ['in', $content_types];
        }
        // 排序
        if( key_exists($order_key, $params) && $params[$order_key] )
            $order                      =   $params[$order_key];
        // 页数
        $page                           =   key_exists($page_key, $params)
            ?   (int)$params[$page_key]
            :   1;
        // 条目数  最多一次显示100条
        $limit                          =   !key_exists($limit_key, $params)
            ?   20
            :   ($params[$limit_key]>100 ? 100 : (int)$params[$limit_key]);
        // 是否初始化
        $init                          =   key_exists($init_key,$params) && ($params[$init_key]==1)
            ?   true
            :   false;

        return [
            'conditions'        =>  $conditions,
            'order'             =>  $order,
            'limit'             =>  compact('page','limit'),
            'init'              =>  $init,
        ];
    }

    public function detail ()
    {
        try{
            (IS_POST && IS_AJAX) || E('非法请求');
            $id             =   I('post.id',false,'int');
            ($id===false) && E('参数缺失');
            $model          =   new OpenclassModel();
            $info           =   $model->relation(true)
                ->field(true)
                ->find($id);
            $info || E('数据不存在');
            $this->ajaxReturn( ['result'=>true,'info'=>$info,'crm_domain'=>C('CRM_DOMAIN')] );
        }catch (Exception $e){
            $this->ajaxReturn([
                'result'        =>  false,
                'error'         =>  $e->getMessage(),
            ]);
        }
    }

    public function snumIncre ()
    {
        $base       =   new BaseController();
        try{
            (IS_POST && IS_AJAX) || E('非法请求');
            $id             =   I('post.id',false,'int');
            ($id===false) && E('参数缺失');
            $model          =   new OpenclassModel();
            $info           =   $model->where(['id'=>['eq',$id]])->setInc('studynum');
            $this->ajaxReturn( ['result'=>true] );
        }catch (Exception $e){
            $this->ajaxReturn([
                'result'        =>  false,
                'error'         =>  $e->getMessage(),
            ]);
        }
    }

}