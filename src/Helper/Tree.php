<?php

namespace TpDataDocking\Helper;

/**
 * 通用的树型类
 * @author Lee
 */
class Tree
{
    protected static $key;
    protected static $pkey;
    protected static $childKey;
    protected static $dataByKey;
    protected static $dataByPkey;

    /**
     * 初始化配置及数据
     *
     * @param string $key 节点唯一标识字段
     * @param string $pkey 父级节点标识字段
     * @param string $childKey 子级集合字段
     * @param array $data 源数据
     *
     * @author wlq
     * @since 1.0 20210820
     */
    public static function initData(
        string $key,
        string $pkey,
        array $data,
        string $childKey = 'children'
    ): void {
        self::$key = $key;
        self::$pkey = $pkey;
        self::$childKey = $childKey;
        $data = array_map(function ($item) {
            $item[self::$pkey] = isset($item[self::$pkey]) ? ($item[self::$pkey] ?: 0) : 0;
            return $item;
        }, $data);
        self::$dataByKey = array_column($data, null, $key);
        self::$dataByPkey = self::arrayColumn($data, $pkey);
    }

    /**
     * 整理为以指定值为键的数组，重复键值合并为子数组
     *
     * @param array $data
     * @param string $pKey
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 2023-03-20
     */
    public static function arrayColumn(array $data, string $pKey = ''): array
    {
        $pKey = $pKey ?: self::$pkey;
        $newData = [];
        foreach ($data as $v) {
            $v[$pKey] = isset($v[$pKey]) ? ($v[$pKey] ?: 0) : 0;
            $newData[$v[$pKey]] = $newData[$v[$pKey]] ?? [];
            $newData[$v[$pKey]][] = $v;
        }
        return $newData;
    }

    /**
     * 生成指定节点下的树结构
     *
     * @param mixed $value     指定节点的值
     * @param null $fun 参数处理方法，例:
     * <pre>
     * function ($v) {
     *      $v['field1'] = date('Y-m-d', v['field1']);
     *      return $v;
     * }
     * </pre>
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 2023-03-20
     */
    public static function makeTree($value, $fun = null): array
    {
        $data = self::$dataByPkey[$value] ?? [];
        $tree = [];
        foreach ($data as $v) {
            $nv = $v[self::$key];
            $pv = $v[self::$pkey];
            if ($fun) {
                $v = $fun($v);
            }
            //防止自定义方法删除了关联关系字段
            $v[self::$key] = $nv;
            $v[self::$pkey] = $pv;
            $v[self::$childKey] = self::makeTree($v[self::$key], $fun);
            $tree[] = $v;
        }
        return $tree;
    }

    /**
     * 获取指定节点下所有的子集列表
     *
     * @param $value
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 2023-03-21
     */
    public static function getAllChildren($value): array
    {
        $data = self::$dataByPkey[$value] ?? [];
        foreach ($data as $v) {
            $children = self::getAllChildren($v[self::$key]);
            $data = array_merge($data, $children);
        }
        return $data;
    }
    /**
     * 获取当前值节点到最高节点的链路
     *
     * @param mixed $value
     * @param array $children
     * @param null $fun 参数处理方法，例:
     * <pre>
     * function ($v) {
     *      $v['field1'] = date('Y-m-d', v['field1']);
     *      return $v;
     * }
     * </pre>
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 2023-03-21
     */
    public static function getParentsLink($value, array $children, $fun = null): array
    {
        if (isset(self::$dataByKey[$value])) {
            $data = self::$dataByKey[$value];
            $nv = $data[self::$key];
            $pv = $data[self::$pkey];
            if ($fun) {
                $data = $fun($data);
            }
            //防止自定义方法删除了关联关系字段
            $data[self::$key] = $nv;
            $data[self::$pkey] = $pv;
            $data[self::$childKey] = $children ?: [];
            return self::getParentsLink($data[self::$pkey], [$data], $fun);
        } else {
            return $children;
        }
    }

    /**
     * 获取当前节点及其所有父级数据集合
     *
     * @param $value
     * @param array $parents
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 2023-03-21
     */
    public static function getParents($value, array $parents = []): array
    {
        if (isset(self::$dataByKey[$value])) {
            $parents[] = self::$dataByKey[$value];
            return self::getParents(self::$dataByKey[$value][self::$pkey], $parents);
        } else {
            return $parents;
        }
    }
}
