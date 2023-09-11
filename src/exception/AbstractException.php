<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-04-27
 * Time: 10:43
 */

namespace tpDataDocking\exception;

use Throwable;

abstract class AbstractException extends \Exception
{
    protected $httpCode    = 200;
    protected $description = '';

    public function __construct(string $message = '', string $description = '', $code = 0, Throwable $previous = null)
    {
        $this->description = $description;
        parent::__construct($message, $code, $previous);
    }

    final public function getHttpCode()
    {
        return $this->httpCode;
    }

    final public function getDescription()
    {
        return $this->description;
    }

}