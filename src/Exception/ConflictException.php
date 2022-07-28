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
 * 传入参数找不到对应数据等（前后端不匹配）
 * Class ConflictException
 * @package TpDataDocking\Exception
 */
class ConflictException extends AbstractException
{
    protected $httpCode = 403;

    public function __construct(string $message = '数据有误', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}