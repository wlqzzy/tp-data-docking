<?php

namespace tpDataDocking\log;

interface LogInterface
{
    /**
     * 获取日志
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public function getLog(): array;

    /**
     * 添加日志
     *
     * @param mixed $data
     *
     * @author wlq
     * @since 1.0 2023-09-11
     */
    public function setLog($data, string $key = ''): void;
}