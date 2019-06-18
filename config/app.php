<?php

return [
    'debug' => env('APP_DEBUG', true),

    'log_only_error_request'=>env('LOG_ONLY_ERROR_REQUEST', true),//是否只记录错误的请求日志，针对请求记录中间件

];
