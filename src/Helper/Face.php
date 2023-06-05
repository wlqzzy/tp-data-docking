<?php

namespace TpDataDocking\Helper;


use TpDataDocking\Exception\ConfigException;
use TpDataDocking\Exception\HttpException;

/**
 * Class BaseApiService
 * @package app
 * @codeCoverageIgnore
 */
trait Face
{
    private string $namespace;
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }
    public function __get($name)
    {
        $class = $this->namespace . ucfirst($name);
        if (class_exists($class) && empty($this->$name)) {
            $this->$name = new $class();
        }
        return $this->$name;
    }
}
