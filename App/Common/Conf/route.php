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
        '/'             =>  'Home/Index/index',
        'test'          =>  'Home/Index/test',

        /* student */
        'api/login'            =>  'Home/Auth/login',
        'api/logout'           =>  'Home/Auth/logout',
        'api/student/info'     =>  'Home/Student/index',
    ],
];