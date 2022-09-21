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

        $logFile = fopen(
            storage_path('logs' . DIRECTORY_SEPARATOR . 'sql-' . date('Y-m-d') . '.log'),
            'a+'
        );

        // 输出日志到文件
        fwrite($logFile, '[' . date('Y-m-d H:i:s') . ']: ——————————————————————————————————————————请求开始' . PHP_EOL);
        fwrite($logFile, '请求地址【' . $request->path() . '】' . PHP_EOL);
        foreach ($logs as $log) {
            // 组装sql
            $sql = $log['query'];
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);
            $query = vsprintf($query, $log['bindings']);

            // 打印sql
            fwrite($logFile, "耗时【 ".$log['time']." ms 】" . $query . PHP_EOL);
        }
        fwrite($logFile, '[' . date('Y-m-d H:i:s') . ']: ——————————————————————————————————————————请求结束' . PHP_EOL . PHP_EOL . PHP_EOL);
        fclose($logFile);

        return $response;
    }
}