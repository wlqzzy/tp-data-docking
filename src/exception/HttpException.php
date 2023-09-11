<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-28
 * Time: 20:18
 */

namespace tpDataDocking\exception;

use Throwable;

/**
 * http请求错误
 * Class HttpException
 * @package tpDataDocking\exception
 */
class HttpException extends AbstractException
{
    protected $httpCode = 500;
    protected $curlInfo;

    public function __construct(string $message = '请求失败', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }

    public function setCurlInfo($info)
    {
        $this->curlInfo = $info;
    }

    public function getCurlInfo()
    {
        return $this->curlInfo;
    }
}