<?php

namespace tpDataDocking\helper;

/**
 * 通用的树型类
 * @author Lee
 * @method static \tpDataDocking\core\Tree init(array $data, string $key = 'id', string $pkey = 'pid', string $childKey = 'children') 初始化init
 * @method static \tpDataDocking\core\Tree setKey(string $key)  设置key
 * @method static \tpDataDocking\core\Tree setPkey(string $pkey)    设置pkey
 * @method static \tpDataDocking\core\Tree setChildKey(string $childKey)    设置childKey
 * @method static \tpDataDocking\core\Tree setData(array $data) 设置数据源
 * @method static array arrayColumn(array $data, string $pKey = '')  整理为以指定值为键的数组，重复键值合并为子数组
 * @method static array getTree($value, $fun = null)    生成指定节点下的树结构
 * @method static array getAllChildren($value)   获取指定节点下所有的子集列表
 * @method static array getParentsLink($value, array $children, $fun = null) 获取当前值节点到最高节点的链路
 * @method static array getParents($value, array $parents = [])  获取当前节点及其所有父级数据集合
 */
class Tree
{
    /**
     * @var \tpDataDocking\core\Tree
     */
    private static $tree;
    /**
     * 创建Facade实例
     * @static
     * @access protected
     * @return object
     */
    protected static function createFacade()
    {
        if (!self::$tree) {
            self::$tree = new \tpDataDocking\core\Tree();
        }
        return self::$tree;
    }

    // 调用实际类的方法
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::createFacade(), $method], $params);
    }
}
