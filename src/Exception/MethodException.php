<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-27
 * Time: 10:42
 */

namespace TpDataDocking\Exception;

use Throwable;

/**
 * 方法请求错误
 * Class MethodException
 * @package TpDataDocking\Exception
 */
class MethodException extends AbstractException
{
    protected $httpCode = 404;

    public function __construct(string $message = '尚不支持该接口', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}