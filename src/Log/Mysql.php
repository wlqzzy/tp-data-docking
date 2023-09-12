<?php

namespace tpDataDocking\log;

use think\facade\Db;

class Mysql implements LogInterface
{
    private $sqlList = [];

    /**
     * 获取日志
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public function getLog(): array
    {
        return $this->sqlList;
    }

    /**
     * 自动记录最后一次sql执行语句
     *
     * @param string $key
     *
     * @author wlq
     * @since 1.0 2023-09-12
     */
    public function autoSetLog(string $key = '')
    {
        $sql = app()->db->getLastSql();
        $this->setLog($sql, $key);
    }

    /**
     * 记录服务请求详情
     *
     * @param mixed $data
     * @param string $key
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public function setLog($data, string $key = ''): void
    {
        if ($key) {
            $this->sqlList[$key] = $data;
        } else {
            $this->sqlList[] = $data;
        }
    }
}