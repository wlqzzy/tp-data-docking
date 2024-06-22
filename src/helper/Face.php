<?php

namespace tpDataDocking\helper;

/**
 * Class BaseApiService
 * @package app
 * @codeCoverageIgnore
 */
abstract class Face
{
    protected $classArr = [];
    protected $classService = [];

    public function __call($name, $arguments)
    {
        $class = $this->classArr[$name] ?? null;
        if ($class) {
            !isset($this->classService[$name]) && $this->classService[$name] = new $class();
            return $this->classService[$name];
        }
        return call_user_func_array([$this, $name], $arguments);
    }
}
