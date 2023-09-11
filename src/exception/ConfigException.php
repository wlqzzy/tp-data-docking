<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-28
 * Time: 20:13
 */

namespace tpDataDocking\exception;

use Throwable;

/**
 * 配置错误
 * Class ConfigException
 * @package tpDataDocking\exception
 */
class ConfigException extends AbstractException
{
    protected $httpCode = 500;

    public function __construct(string $message = '服务器内部错误', string $description = '配置不正确', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}