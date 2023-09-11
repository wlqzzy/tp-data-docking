<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-27
 * Time: 10:42
 */

namespace tpDataDocking\exception;

use Throwable;

/**
 * 未登录错误
 * Class NotLoginException
 * @package tpDataDocking\exception
 */
class NotLoginException extends AbstractException
{
    protected $httpCode = 401;

    public function __construct(string $message = '未登录', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}