<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-28
 * Time: 20:23
 */

namespace TpDataDocking\Exception;

use Throwable;

/**
 * 服务错误
 * Class ServerException
 * @package TpDataDocking\Exception
 */
class ServerException extends AbstractException
{
    protected $httpCode = 500;

    public function __construct(string $message = '服务器内部错误', string $description = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $description, $code, $previous);
    }
}