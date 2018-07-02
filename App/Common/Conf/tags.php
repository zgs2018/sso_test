<?php

return [
    'app_init'                  =>  [
        'Common\Behavior\CheckEnvBehavior',
        'Common\Behavior\ShutDownBehavior',
        'Common\Behavior\CheckOriginBehavior',
        'Common\Behavior\CheckSignInBehavior',
    ],

    'view_filter'               =>  [
        'Behavior\TokenBuildBehavior'
    ],

    'authorized_handle'    =>  [
        'Auth\Behavior\InterceptBehavior',
    ],
];