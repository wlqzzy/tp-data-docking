<?php

namespace tpDataDocking;

/**
 * 数据对接基类，建议继承本类通过property增加ide提示
 * @property \app\operation\face\Db $db
 * @property \app\operation\face\Api $api
 */
class Operation
{
    private static $instance;
    /**
     * 门面类命名空间
     * @var string
     */
    private $namespaceBase = "\\app\\operation\\";

    /**
     * 初始化
     *
     * @return Operation
     *
     * @author wlq
     * @since 1.0 2022-07-28
     */
    public static function init(): Operation
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setNamespace(string $namespaceBase): Operation
    {
        $this->namespaceBase = $namespaceBase;
        return $this;
    }


    /**
     * 获取数据操作对象，默认\app\operation\face
     *
     * @param $name
     * @return mixed
     *
     * @author wlq
     * @since 1.0 2022-01-12
     */
    public function __get($name)
    {
        if (empty($this->$name)) {
            $class = $this->namespaceBase . 'face\\' . ucfirst($name);
            if (class_exists($class)) {
                $this->$name = new $class($this->namespaceBase . $name . '\\');
            }
        }
        return $this->$name;
    }
}