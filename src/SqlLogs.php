<?php

namespace HeiFen\LaravelSqlLogs;

class SqlLogs
{
    public function handle($request, \Closure $next)
    {
        \DB::connection()->enableQueryLog();
        
        $response = $next($request);

        // 获取sql日志信息
        $logs = \DB::getQueryLog();
        // dd($request->path());

        // 输出日志到文件
        \Log::info('——————————————————————————————————————————请求开始');
        \Log::info('请求地址【' . $request->path() . '】');
        foreach ($logs as $log) {
            // 组装sql
            $sql = $log['query'];
            foreach ($log['bindings'] as $replace){
                $value = is_numeric($replace) ? $replace : "'".$replace."'";
                $sql = preg_replace('/\?/', $value, $sql, 1);
            }

            \Log::info($sql);
            \Log::info("耗时【 ".$log['time']." ms 】");
        }
        \Log::info('——————————————————————————————————————————请求结束');

        return $response;
    }
}