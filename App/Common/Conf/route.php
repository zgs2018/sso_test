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

        /* student */
        'api/login'                 =>  'Home/Auth/login',  // 登陆
        'api/logout'                =>  'Home/Auth/logout', // 登出
        'api/profile'               =>  'Home/Student/profile', // 学员信息
        'api/upload/headpic'        =>  'Home/Student/uploadHeadpic', // 上传头像
        'api/student/info'          =>  'Home/Student/index', // 初始化主页数据
        'api/profile/setting'       =>  'Home/Student/setting', // 个人信息修改

        /* letter */
        'api/letter/list'           =>  'Home/Letter/index',// 获取信件列表
        'api/letter/read'           =>  'Home/Letter/read',// 信件标记已读
    ],
];