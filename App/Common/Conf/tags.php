<?php

return [
    'app_init'          =>  [
        'Common\Behavior\CheckEnvBehavior',
        'Common\Behavior\ShutDownBehavior',
    ],
    'view_filter'       =>  ['Behavior\TokenBuildBehavior'],
];