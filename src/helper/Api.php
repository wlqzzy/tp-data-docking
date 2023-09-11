<?php

namespace tpDataDocking\helper;

use HttpClient\Core\Response;
use HttpClient\HttpClient;
use tpDataDocking\exception\ConfigException;
use tpDataDocking\exception\HttpException;
use tpDataDocking\LibBaseLog;

/**
 * 三方服务trait
 */
trait Api
{
    protected $client;
    protected $service;
//    protected $consul;
    protected $name;
    protected $errorField = 'msg';
    protected $descField = 'description';
    protected $codeField = 'code';

    /**
     * @throws ConfigException
     */
    public function __construct()
    {
        if (empty($this->serviceName)) {
            throw new ConfigException('未配置' . $this->name . '服务地址');
        }
        $url = env('service.' . $this->serviceName);
        if (!$url) {
            throw new ConfigException('缺少' . $this->name . '服务地址配置');
        }
        $this->client($url);
    }

    /**
     * 根据地址初始化请求类
     *
     * @param string $url
     * @return HttpClient
     *
     * @author aiChenK
     * @version 1.1
     */
    final protected function client(string $url): HttpClient
    {
        if (isset($this->client[$url])) {
            return $this->client[$url];
        }
        $this->client[$url] = new HttpClient($url);
        $this->client[$url]->setConnExceptionHandle(function ($msg) {
            throw new HttpException('连接失败', $this->name . ':' . $msg);
        });
        return $this->client[$url];
    }

    /**
     * 获取请求错误
     *
     * @param Response $response
     * @param string $field
     * @return mixed|string
     *
     * @author aiChenK
     * @version 1.1
     */
    protected function getError(Response $response, string $field = '')
    {
        $body = $response->getJsonBody();
        if (isset($body[$field])) {
            return is_string($body[$field]) ? $body[$field] : json_encode($body[$field], JSON_UNESCAPED_UNICODE);
        }
        return '未知错误：' . $response->getBody();
    }

    /**
     * 抛出http异常
     *
     * @param Response $response
     * @param string $msg
     * @param string $errField
     * @param string $descField
     * @param string $codeField
     * @param bool $saveLog
     * @throws HttpException
     *
     * @author aiChenK
     * @version 1.5 2022-06-27 wlq 默认记录非get请求记录
     * @version 1.3
     */
    protected function throwIfError(
        Response $response,
        string $msg,
        string $errField = '',
        string $descField = '',
        string $codeField = '',
        bool $saveLog = true
    ): void {
        $info = $response->getInfo();
        $data = $response->getJsonBody();
        $codeField = $codeField ?: $this->codeField;
        //http状态为200 且 返回信息不存在code参数或code参数为200，则为请求成功
        if (
            $response->isSuccess()
            && (
                !isset($data[$codeField])
                || (isset($data[$codeField]) && floor($data[$codeField] / 100) == 2)
            )
        ) {
            //默认记录非get请求记录
            if ($saveLog && strtolower($info->method) != 'get') {
                LibBaseLog::get()->service->setLog($info);
            }
            return;
        }
        if ($response->isSuccess()) {
            //默认记录非get请求记录
            if ($saveLog && strtolower($info->method) != 'get') {
                LibBaseLog::get()->service->setLog($info);
            }
            return;
        }
        $errorMsg = $this->getError($response, $errField ?: $this->errorField);
        $descMsg  = $this->name . ':' . $this->getError($response, $descField ?: $this->descField);
        $e = new HttpException($errorMsg ?: $msg, $descMsg);
        $e->setCurlInfo($info);
        LibBaseLog::get()->service->setLog($info);
        throw $e;
    }
}
