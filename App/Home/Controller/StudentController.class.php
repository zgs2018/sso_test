<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Model\CourseMdoel;
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

        $this->ajaxReturn( [
            'result'    =>  true,
            'schedule'  =>  $schedule,
            'period'    =>  $period,
        ] );
    }

    public function info ()
    {
        $studentModel           =   new StudentModel();

        $result                 =   $studentModel->alias('s')->relation('letters')
            ->where(['s.id'=>['eq',session('_student.id')]])->select();

        dump($result);
    }
}