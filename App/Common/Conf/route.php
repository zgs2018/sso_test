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
        'curl/post'                 =>  'Home/Index/post',
        'curl/get'                  =>  'Home/Index/get',

        'soap/server'               =>  'Home/Index/server',
    ],
];