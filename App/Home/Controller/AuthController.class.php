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
        return exists_key( 'username', $params )
            && exists_key( 'password', $params )
            && $params['username']
            && $params['password'];
    }

    protected function checkResetParams ($params)
    {
        return exists_key( 'oldpasswd', $params )
            && exists_key( 'newpasswd', $params )
            && exists_key( 'newpasswd2', $params )
            && $params['oldpasswd']
            && $params['newpasswd']
            && $params['newpasswd2']
            && ($params['newpasswd']===$params['newpasswd2']);
    }
}