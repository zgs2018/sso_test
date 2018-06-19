<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Model\CourseMdoel;
use Home\Model\LetterModel;
use Home\Model\PeriodModel;
use Home\Model\StudentModel;

class StudentController extends BaseController
{
    public function index ()
    {
        $s_id           =   session('_student')['id'];
        // TODO 购买产品、课程列表、课表信息
        $schedule       =   [];
        $course         =   [];
        $product        =   [];
        $studentModel           =   new StudentModel();
        $periodModel            =   new PeriodModel();
        $letterModel            =   new LetterModel();
        $scheduleData           =   $studentModel->studentSchedule($s_id);
        $now                    =   time();
        foreach ($scheduleData as $key => $value){
            // 排课状态  是否已经结束
            $value['status']        =   ($now>$value['stamp']) ? -1 : 1;
            $schedule[date('Y.m.d',$value['stamp'])][]    =   $value;
        }
        // 课程
        // 班级
        $period                 =   $periodModel->period_list(['s.id'=>['eq',$s_id]]);
        $period                 =   array_map( function($p){
            $p['course_pic']        =   'http://192.168.0.160:8087'.substr( $p['course_pic'],1 );
            return $p;
        },$period );
        // 排课

        // 学生信息
        $studentInfo            =   $studentModel->field('password,remark',true)->relation('profile')->find($s_id);
        // 未读信息数量
        $letterUnreadCount      =   $letterModel->unreadCount(['student_id'=>['eq',$s_id]]);


        $this->ajaxReturn( [
            'result'                    =>  true,
            'schedule'                  =>  $schedule,
            'period'                    =>  $period,
            'letterUnreadCount'         =>  $letterUnreadCount,
            'info'                      =>  $studentInfo,
        ] );
    }

    public function letter ()
    {
        $letterModel            =   new LetterModel();

        $result                 =   $letterModel->where(['student_id'=>['eq',session('_student.id')]])
            ->order('status asc,create_at desc')
            ->select();


        $this->ajaxReturn( [
            'result'            =>  true,
            'lists'             =>  $result,
            'desc'              =>  'status:信件状态 0.未读 1.已读 2.标记; from_type:来源类型 1.教师 2.学员 3.系统通知',
            '_sql'              =>  $letterModel->_sql(),
        ] );
    }
}