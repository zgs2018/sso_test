<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use http\Env\Request;
use Think\Controller;

class ApplyController extends BaseController
{
    protected $stdt_id = '';

    public function _initialize()
    {
        if ($current = session('_student'))
        {
            $this->stdt_id = $current['id'];
        }else{
            $this->ajaxReturn(['status'=>false,'msg'=>'请先登录']);
        }
    }

    /**
     * 获取指定学员的申请项目
     */
    public function myApply()
    {
        $tag =I('post.tag')?:'';// 申请类别

        // 按学员搜索
        $condition = ' `student_id` = '.$this->stdt_id;
        // 按申请类别搜索
        if ($tag) $condition .= ' AND `tag` = '.$tag;
        // 读取数据
        $list = D('MaterialsApply')->getMyApply($condition);

        if ($list)
        {
            foreach ($list as $key => $val) { // 获取每个申请项目中的所需材料
                $_materials_ids = json_decode($list[$key]['materials']); // 转换样本id
                $materials_ids = implode(',',$_materials_ids); // 拼接样本 id
                if ($materials_ids)
                {
                    $list[$key]['materials'] = D('MaterialsSample')->getMySample('id in ('.$materials_ids.')'); // 获取样本
                }else{
                    $list[$key]['materials'] = [];
                }
            }
        }

        // 返回数据
        $this->ajaxReturn(['status'=>true,'data'=>$list]);
    }

    public function myMaterials()
    {
        // 准备条件
        $condition = ' student_id = '.$this->stdt_id;

        // 读取数据
        $list = D('Materials')->getMaterials($condition);

        // 暂时用不到
        foreach ($list as $k => $v) {//'project_name,status'
            $list[$k]['outer'] = D('MaterialsApply')->getMaterials(' id='.$list[$k]['program_id']);
        }

        $this->ajaxReturn(['status'=>true,'data'=>$list]);

    }

    /**
     * 获取指定学员的申请项目材料样本
     */
    public function myMaterialsSample()
    {
        // 按学员搜索
        $condition = ' student_id = '.$this->stdt_id;

        // 读取当前学员已申请项目
        $list = D('MaterialsApply')->getMyApply($condition);

        $end_res = []; // 准备存放最终结果的数组
        // 如果当前学员有申请项目,则将数据转换为前端需要的特定格式
        if ($list)
        {
            foreach ($list as $key => $val) { // 获取每个申请项目中的所需材料
                $_materials_ids = json_decode($list[$key]['materials']); // 转换样本id
                $materials_ids = implode(',',$_materials_ids); // 拼接样本 id
                if ($materials_ids)
                {
                    $res = D('MaterialsSample')->getMySample('id in ('.$materials_ids.')'); // 获取样本
                }else{
                    $res = [];
                }
                if ($res) // 如果数据不为空 (当前申请项目需要提交材料)
                {
                    foreach ($res as $k => $v) { // 在材料中添加当前项目名称字段(满足页面显示格式)
                        $res[$k]['_tagname'] = $list[$key]['project_name'];
                    }
                }
                $end_res = array_merge($end_res,$res); // 合并数据
            }
        }

        // 返回数据
        $this->ajaxReturn(['status'=>true,'data'=>$end_res]);
    }

    /**
     * 删除当前学员的指定材料
     */
    public function delCurrentUserMaterials()
    {
        // 获取关键参数
        if (!$id = I('post.id')) $this->ajaxReturn(['status'=>false,'msg'=>'Lack of key parameters.']);

        $materials = D('Materials')->findMyMaterials($id);

        // TODO 这里确认学员登陆后学员信息 然后匹配当前学员的ID是否等同于要删除材料中的student_id,如果相等,则删除,否则定义为非法操作.
        // 如果当前材料不存在 或材料与学员不匹配
        if ( !$materials || ($materials['student_id'] != $this->stdt_id))
        {
            $this->ajaxReturn(['status'=>false,'msg'=>'Materials not found.']);
        }

        // 执行删除
        try {
            $res = D('Materials')->delOne($id);
            if (!$res) $this->ajaxReturn(['status'=>false,'msg'=>'删除失败,请稍后再试,或联系客服人员']);
        } catch (\Exception $exception) {
            $this->ajaxReturn(['status'=>false,'msg'=>'系统维护,请联系工作人员','outer'=>$exception->getMessage()]);
        }

        // 删除成功,返回列表页
        $this->ajaxReturn(['status'=>true,'msg'=>'success.']);
    }

    /**
     * 材料添加
     */
    public function stuMaterialsAdd()
    {

        // 接收表单数据
        if (!($data = I('post.')) || !($id = I('post.program_id'))) $this->ajaxReturn(['status'=>false,'msg'=>'Lack of key parameters.']);

        // 是否含有文件信息
        if (!is_array($data['files']) || count($data['files'],1) < 1 ) $this->ajaxReturn(['status'=>false,'msg'=>'Lack of file.']);

        // 生成时间
        $data['create_time'] = time();
        $data['program_id'] = $id;

        // 过滤数据
        foreach ($data['files'] as $k => $v)
        {
            $data['file'] = $v['path'];
            $data['name'] = $v['name'];

            // 执行添加操作
            try {
                $res = D('Materials')->addMaterials($data);
                if (!$res) $this->ajaxReturn(['status'=>false,'msg'=>'添加失败,请检确认添加数据是否合理 或 联系管理员! ']);
            } catch (\Exception $exception) {
                $this->ajaxReturn(['status'=>false,'msg'=>$exception->getMessage()]);
            }

        }

        // 添加成功,返回列表页
        $this->ajaxReturn(['status'=>true,'data'=>'success.']);

    }
}