<?php
namespace Home\Model;

use Common\Model\EModel;

class StudentModel extends EModel
{
    protected $tableName            =   'students';

    public function getStudentProduct ()
    {

    }
    public function studentSchedule ($studentId)
    {
        // TODO student -> period_student -> [ course_period, course_schedule ] -> [ course, course_section ]

        $_where         =   [
            's.id'              =>  ['eq',$studentId],
            'c.status'          =>  ['eq',1],
            'c_per.status'      =>  ['eq',1],
        ];
        $_field         =   "c.name course_name,c_per.name period_name,c_sec.name section_name,c_sch.period_id,
        mxu1.full_name headmaster,mxu2.full_name teacher,c_sec.title,c_sch.start_time,c_sec.duration,
        date_add(c_sch.start_time,INTERVAL c_sec.duration MINUTE) end_time,UNIX_TIMESTAMP(c_sch.start_time) stamp";
        return $this->field($_field)
            ->join("s LEFT JOIN {$this->dbName}.period_student p_s ON s.id = p_s.student_id")
            ->join("LEFT JOIN {$this->dbName}.course_period c_per ON p_s.period_id = c_per.id")
            ->join("LEFT JOIN {$this->dbName}.course_schedule c_sch ON p_s.period_id = c_sch.period_id")
            ->join("LEFT JOIN {$this->dbName}.course c ON c.id = c_per.course_id")
            ->join("LEFT JOIN {$this->dbName}.course_section c_sec ON c_sec.id = c_sch.section_id")
            ->join("LEFT JOIN mxcrm.mx_user mxu1 ON mxu1.user_id = c_per.headmaster_id")
            ->join("LEFT JOIN mxcrm.mx_user mxu2 ON mxu2.user_id = c_sch.teacher_id")
            ->where($_where)
            ->select();
    }

    public function student_login ($username,$password)
    {
        $studentInfo            =   $this->field('id,code,realname,mobile,password')
            ->where(['mobile'=>['eq',$username], 'status'=>['eq',1]])
            ->find();

        return ( $studentInfo
            && $studentInfo['mobile'] === $username
            && password_verify($password, $studentInfo['password']) )
            ?   $studentInfo
            :   false;
    }
}