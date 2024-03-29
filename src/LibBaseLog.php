<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-07-30
 * Time: 16:49
 */

namespace tpDataDocking;

use tpDataDocking\log\Mysql;
use tpDataDocking\log\Service;

/**
 * LibBaseLog 操作记录基础类
 *
 * @property Service $service
 * @property Mysql $mysql
 */
class LibBaseLog
{
    /**
     * @var LibBaseLog
     */
    private static $face;

    private $logClass = [
        'service' => Service::class,
        'mysql' => Mysql::class
    ];

    /**
     * 获取日志记录对象
     *
     * @param string $logKey
     * @return LibBaseLog | Service | Mysql
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public static function get(string $logKey = '')
    {
        if (!self::$face) {
            self::$face = new self();
        }
        return $logKey ? self::$face->$logKey : self::$face;
    }

    public function __get($name)
    {
        if (isset($this->logClass[$name]) && empty($this->$name)) {
            $this->$name = new $this->logClass[$name]();
        }
        return $this->$name;
    }
}
