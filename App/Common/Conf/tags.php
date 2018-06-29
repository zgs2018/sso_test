<?php

return [
    'app_init'          =>  [
        'Common\Behavior\CheckEnvBehavior',
        'Common\Behavior\ShutDownBehavior',
        'Common\Behavior\CheckOriginBehavior',
    ],
    'view_filter'       =>  ['Behavior\TokenBuildBehavior'],
];