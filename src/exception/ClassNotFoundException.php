<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-28
 * Time: 20:16
 */

namespace tpDataDocking\exception;

use Throwable;

/**
 * 找不到对应类
 * Class ClassNotFoundException
 * @package tpDataDocking\exception
 */
class ClassNotFoundException extends AbstractException
{
    protected $httpCode = 500;

    public function __construct(string $message = '服务器内部错误', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}