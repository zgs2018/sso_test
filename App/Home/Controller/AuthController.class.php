<?php

namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Model\StudentModel;
use http\Env\Response;
use Think\Controller;
use Think\Exception;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        IS_JSONP && $this->_origin();
        if(ACTION_NAME == 'logout') return ;

    }

    public function login_view (){
        $this->display();
    }

    public function login ()
    {
        try{
            $this->isLoginYet() && E('您已经登陆了');
            // TODO change
            if( IS_POST && IS_AJAX ){
                $params         =   I('post.');
                // 参数检验
                $this->checkLoginParams( $params ) || E('用户名或密码不合法!',203);
                // 证书验证
                $studentModel       =   new StudentModel();
                $params             =   array_map( 'trim', $params );
                ( $info=$studentModel->student_login( $params['username'], $params['password'] ) ) || E('用户名或密码不合法!',203);
                //
                session('_student', $info);
                //
                $result             =   [
                    'result'            =>  true,
                    'msg'               =>  '登陆成功!',
                ];
                $this->response( $result, '/' );
            }
            E('非法操作',200);
        }catch (Exception $e){
            $return = [
                'result'        =>  false,
                'msg'           =>  $e->getMessage(),
            ];
            $url            =   $e->getCode() == 203 ? U('Auth/login_view') : null;

            $this->response($return,$url);
        }
    }

    public function logout ()
    {
        $result = [
            'result'    =>  false,
            'msg'       =>  '你还没有登录',
        ];
        $this->isLoginYet() || $this->response( $result, '/' );

        session(null);
        $result         =   [
            'result'        =>  true,
            'msg'           =>  '退出登录成功',
        ];
        $this->response($result, U('Auth/login_view'));
    }

    public function reset ()
    {
        try{
            if( IS_POST && IS_AJAX ){
                $params                 =   I('post.');
                $model                  =   new StudentModel();
                $this->checkResetParams($params) || E('参数有误');
                $model->student_login( session('_student.mobile'), $params['oldpasswd'] ) || E('旧密码不正确');
                $data                   =   [
                    'id'        =>  (int)session('_student.id'),
                    'password'  =>  password_hash( trim($params['newpasswd']), PASSWORD_BCRYPT )
                ];

                $model->field('password')->save($data)===false && E($model->getDbError());

                $this->ajaxReturn( ['result'=>true] );
            }
            E('非法操作');
        }catch (Exception $e){
            $result                 =   [
                'result'                =>  false,
                'error'                 =>  $e->getMessage(),
            ];
            $this->ajaxReturn( $result );
        }
    }

    public function isLoginYet ()
    {
        return session('?_student');
    }

    protected function checkLoginParams ($params)
    {
        return array_key_exists( 'username', $params )
            && array_key_exists( 'password', $params )
            && $params['username']
            && $params['password'];
    }

    protected function checkResetParams ($params)
    {
        return array_key_exists( 'oldpasswd', $params )
            && array_key_exists( 'newpasswd', $params )
            && array_key_exists( 'newpasswd2', $params )
            && $params['oldpasswd']
            && $params['newpasswd']
            && $params['newpasswd2']
            && ($params['newpasswd']===$params['newpasswd2']);
    }

    protected function _origin ()
    {
        // 请求来源
        $http_origin                =   false;
        if( array_key_exists( 'HTTP_ORIGIN', $_SERVER ) && $_SERVER['HTTP_ORIGIN'] ){
            $http_origin        =   $_SERVER['HTTP_ORIGIN'];
        }elseif( array_key_exists( 'HTTP_REFERER', $_SERVER ) && $_SERVER['HTTP_REFERER'] ){
            $http_origin        =   $_SERVER['HTTP_REFERER'];
        }
        // 来源判断
        if( $http_origin === false )    return ;
        // 获取来源主域
        preg_match("/^(?:http|https):\/\/([^\/]+)/", $http_origin, $matches);

        if( !array_key_exists( 1, $matches ) )
            return ;

        header('Access-Control-Allow-Origin: '.$matches[0]);
        header("Access-Control-Allow-Methods: POST, GET");
        header('Access-Control-Allow-Headers:x-requested-with');
        header("Access-Control-Allow-Credentials: true");
        return;
    }
}