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
 * 权限错误，无操作权限
 * Class PowerException
 * @package TpDataDocking\Exception
 */
class PowerException extends AbstractException
{
    protected $httpCode = 403;

    public function __construct(string $message = '缺少权限', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}