<?php

// +----------------------------------------------------------------------
// | 路由配置 ： 规则路由、静态路由
// +----------------------------------------------------------------------

return [
    'URL_ROUTE_RULES'       =>  [
        // Home




        // 定时任务
        'crontab/:type/[:time\d]'       =>  'Crontab/:1/index',
    ],


    'URL_MAP_RULES'         =>  [
        '/'                         =>  'Home/Index/index',
        'test'                      =>  'Home/Index/test',
        'api/isLogin'               =>  'Home/Index/isLogin',

        /* student */
        'api/login'                 =>  'Home/Auth/login',  // 登陆
        'api/logout'                =>  'Home/Auth/logout', // 登出
        'api/reset'                 =>  'Home/Auth/reset', // 密码重置
        'api/profile'               =>  'Home/Student/profile', // 学员信息
        'api/upload/headpic'        =>  'Home/Student/uploadHeadpic', // 上传头像
        'api/student/info'          =>  'Home/Student/index', // 初始化主页数据
        'api/profile/setting'       =>  'Home/Student/setting', // 个人信息修改

        /* letter */
        'api/letter/list'           =>  'Home/Letter/index',// 获取信件列表
        'api/letter/read'           =>  'Home/Letter/read',// 信件标记已读

        'api/myapply'               =>  'Apply/Apply/myApply',  // 我的申请
        'api/mymaterials'           =>  'Apply/Apply/myMaterials',  // 我的材料
        'api/mysample'              =>  'Apply/Apply/myMaterialsSample',  // 我的样本
        'api/studelmaterials'       =>  'Apply/Apply/delCurrentUserMaterials',  // 删除材料
        'api/stuaddmaterials'       =>  'Apply/Apply/stuMaterialsAdd',  // 添加材料





        /* 手机端路由 */
        'm'                         =>  'Mobile/Index/index', // 个人中心
        'm/login'					=>	'Mobile/Index/login',//登陆页面'
        'm/course'			        =>	'Mobile/Index/course',//我的班级'
        'm/course/hour'		        =>	'Mobile/Index/hour',//查看课节'
        'm/apply'				    =>	'Mobile/Index/apply',//我的申请'
        'm/visa'				    =>	'Mobile/Index/visa',//签证办理'
        'm/seo'				        =>	'Mobile/Index/seo',//SEO申请'
        'm/material'			    =>	'Mobile/Index/material',//我的材料'
        'm/message'			        =>	'Mobile/Index/message',//消息列表
        'm/message/details'	        =>	'Mobile/Index/messageDetails',//消息详情
        'm/set'	                    =>	'Mobile/Index/set',//个人信息


        /* 公开课路由 */
        'api/open'                  =>  'Open/Index/index',// 列表
        'api/open/detail'           =>  'Open/Index/detail',// 详情
    ],
];