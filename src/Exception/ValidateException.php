<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-28
 * Time: 20:17
 */

namespace TpDataDocking\Exception;

use Throwable;

/**
 * 验证错误
 * Class ValidateException
 * @package TpDataDocking\Exception
 */
class ValidateException extends AbstractException
{
    protected $httpCode = 400;

    public function __construct(string $message = '验证失败', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}