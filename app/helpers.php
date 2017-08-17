<?php


if (!function_exists('errorLog')) {
    /**
     * 记录错误日志
     * @param Exception $e
     * @param string $str
     */
    function errorLog(Exception $e, $str)
    {
        $str = $str . '操作失败：';
        $msg = sprintf(
            '%s:%s Code:%s Line:%s ',
            $str,
            $e->getMessage(),
            $e->getCode(),
            $e->getLine()
        );
        Log::error($msg);
    }
}

if (! function_exists('qiniu_path')) {
    /**
     * 返回七牛资源路径
     * @param $fileName
     * @return string
     */
    function qiniu_path($fileName)
    {
        return config('filesystems.disks.qiniu.domain') .'/'.$fileName;
    }
}
