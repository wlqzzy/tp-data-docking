<?php

namespace TpDataDocking\Log;

class Service extends Base
{
    private $traceId = '';
    private $spanId = '';
    private $curlInfo = [];

    /**
     * 记录traceId
     *
     * @param string $trace
     * @return Service
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public function setTraceId(string $trace = ''): Service
    {
        $this->traceId = $trace;
        return $this;
    }

    /**
     * 记录traceId
     *
     * @param string $spanId
     * @return Service
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public function setSpanId(string $spanId = ''): Service
    {
        $this->spanId = $spanId;
        return $this;
    }
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
        return [
            'traceId' => $this->traceId,
            'curlList' => $this->curlInfo
        ];
    }

    /**
     * 记录服务请求详情
     *
     * @param $curlInfo
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public function setLog($curlInfo): void
    {
        if ($this->spanId) {
            $this->curlInfo[$this->spanId] = $curlInfo;
            $this->spanId = '';
        } else {
            $this->curlInfo[] = $curlInfo;
        }
    }
}