<?php

namespace tpDataDocking\core;

class Tree
{
    protected $key = 'id';
    protected $pkey = 'pid';
    protected $childKey = 'children';
    protected $dataByKey;
    protected $dataByPkey;

    /**
     * 初始化配置及数据
     *
     * @param array $data 源数据
     * @param string $key 节点唯一标识字段
     * @param string $pkey 父级节点标识字段
     * @param string $childKey 子级集合字段
     * @return $this
     *
     * @author wlq
     * @since 1.0 2023-09-08
     */
    public function init(
        array $data,
        string $key = 'id',
        string $pkey = 'pid',
        string $childKey = 'children'
    ): Tree {
        $this->setKey($key);
        $this->setPkey($pkey);
        $this->setChildKey($childKey);
        $this->setData($data);
        return $this;
    }

    /**
     * 设置key
     *
     * @param string $key
     * @return $this
     *
     * @author wlq
     * @since 1.0 2023-09-08
     */
    public function setKey(string $key): Tree
    {
        $this->key = $key ?: $this->key;
        return $this;
    }

    /**
     * 设置pkey
     *
     * @param string $pkey
     * @return $this
     *
     * @author wlq
     * @since 1.0 2023-09-08
     */
    public function setPkey(string $pkey): Tree
    {
        $this->pkey = $pkey ?: $this->pkey;
        return $this;
    }

    /**
     * 设置childKey
     *
     * @param string $childKey
     * @return $this
     *
     * @author wlq
     * @since 1.0 2023-09-08
     */
    public function setChildKey(string $childKey): Tree
    {
        $this->childKey = $childKey ?: $this->childKey;
        return $this;
    }

    /**
     * 设置数据源
     *
     * @param array $data
     * @return $this
     *
     * @author wlq
     * @since 1.0 2023-09-08
     */
    public function setData(array $data): Tree
    {
        $data = array_map(function ($item) {
            $item[$this->pkey] = empty($item[$this->pkey]) ? 0 : $item[$this->pkey];
            return $item;
        }, $data);
        $this->dataByKey = array_column($data, null, $this->key);
        $this->dataByPkey = $this->arrayColumn($data);
        return $this;
    }

    /**
     * 整理为以指定值为键的数组，重复键值合并为子数组
     *
     * @param array $data
     * @param string $pKey
     * @return array
     *
     * @author wlq
     * @since 1.0 2023-09-08
     */
    public function arrayColumn(array $data, string $pKey = ''): array
    {
        $pKey = $pKey ?: $this->pkey;
        $newData = [];
        foreach ($data as $v) {
            $v[$pKey] = empty($v[$pKey]) ? 0 : $v[$pKey];
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
    public function getTree($value, $fun = null): array
    {
        $data = $this->dataByPkey[$value] ?? [];
        $tree = [];
        foreach ($data as $v) {
            $nv = $v[$this->key];
            $pv = $v[$this->pkey];
            if ($fun) {
                $v = $fun($v);
            }
            //防止自定义方法删除了关联关系字段
            $v[$this->key] = $nv;
            $v[$this->pkey] = $pv;
            $v[$this->childKey] = $this->getTree($v[$this->key], $fun);
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
    public function getAllChildren($value): array
    {
        $data = $this->dataByPkey[$value] ?? [];
        foreach ($data as $v) {
            $children = $this->getAllChildren($v[$this->key]);
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
    public function getParentsLink($value, array $children, $fun = null): array
    {
        if (isset($this->dataByKey[$value])) {
            $data = $this->dataByKey[$value];
            $nv = $data[$this->key];
            $pv = $data[$this->pkey];
            if ($fun) {
                $data = $fun($data);
            }
            //防止自定义方法删除了关联关系字段
            $data[$this->key] = $nv;
            $data[$this->pkey] = $pv;
            $data[$this->childKey] = $children ?: [];
            return $this->getParentsLink($data[$this->pkey], [$data], $fun);
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
    public function getParents($value, array $parents = []): array
    {
        if (isset($this->dataByKey[$value])) {
            $parents[] = $this->dataByKey[$value];
            return $this->getParents($this->dataByKey[$value][$this->pkey], $parents);
        } else {
            return $parents;
        }
    }
}