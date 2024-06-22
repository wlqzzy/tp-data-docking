<?php

namespace tpDataDocking\helper;

/**
 * 数据对接基类，建议继承本类通过db()与api方法增加ide提示
 */
abstract class Operation
{
    protected static $instances = [];
    protected static $db;
    protected static $api;

    /**
     * db 获取db门面实例
     *
     * @return mixed
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    public static function db()
    {
        if (!self::$db) {
            static::setDbFace();
        }
        return self::$db;
    }

    /**
     * setDbFace 获取db门面实例
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    protected static function setDbFace()
    {
    }
    /**
     * api 获取api门面实例
     *
     * @return mixed
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    public static function api()
    {
        if (!self::$api) {
            static::setApiFace();
        }
        return self::$api;
    }

    /**
     * setApiFace 获取api门面实例
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    protected static function setApiFace()
    {
    }
}
