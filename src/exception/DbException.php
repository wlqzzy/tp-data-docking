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
 * 数据库处理错误
 * Class DbException
 * @package tpDataDocking\exception
 */
class DbException extends AbstractException
{
    protected $httpCode = 500;

    public function __construct(string $message = '数据处理失败', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}