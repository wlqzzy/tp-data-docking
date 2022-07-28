<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-07-30
 * Time: 16:49
 */

namespace TpDataDocking;

use TpDataDocking\Log\Service;

/**
 * LibBaseLog 操作记录基础类
 *
 * @property Service $service
 */
class LibBaseLog
{
    /**
     * @var LibBaseLog
     */
    private static $face;

    private $logClass = [
        'service' => Service::class
    ];

    /**
     * 获取日志记录对象
     *
     * @param string $logKey
     * @return LibBaseLog | Service
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

    /**
     * 获取操作记录
     *
     * @param string $logKey
     * @return mixed
     *
     * @author wlq
     * @since 1.0 2022-06-27
     */
    public static function getLog(string $logKey = '')
    {
        $return = [];
        if ($logKey) {
            $return = self::get($logKey)->getLog();
        } else {
            foreach (self::$face->logClass as $key) {
                $return[$key] = self::get($logKey)->getLog();
            }
        }
        return $return;
    }

    public function __get($name)
    {
        if (isset($this->logClass[$name]) && empty($this->$name)) {
            $this->$name = new $this->logClass[$name]();
        }
        return $this->$name;
    }
}
