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
 * 参数传入错误
 * Class ParamException
 * @package TpDataDocking\Exception
 */
class ParamException extends AbstractException
{
    protected $httpCode = 400;

    public function __construct(string $message = '参数错误', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}