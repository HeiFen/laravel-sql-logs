<?php

namespace HeiFen\LaravelSqlLogs;

use Closure;
use Illuminate\Support\Facades\Log;

class SqlLogs
{
    public function handle($request, Closure $next)
    {
        \DB::connection()->enableQueryLog();
        // 获取sql日志信息
        $logs = \DB::getQueryLog();

        // 输出日志到文件
        Log::info($logs);

        return $next($request);
    }
}