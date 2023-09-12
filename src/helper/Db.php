<?php

namespace tpDataDocking\helper;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\Model;
use tpDataDocking\LibBaseLog;

/**
 * tp数据库操作trait
 */
trait Db
{
    /**
     * 数据模型名称
     *
     * @var string
     */
    protected $modelName;
    /**
     * @var bool 是否自动记录sql
     */
    protected $autoLogSqlStatus = false;

    /**
     * 设置是否自动记录sql
     *
     * @param bool $status
     * @return $this
     *
     * @author wlq
     * @since 1.0 2023-09-12
     */
    public function setAutoLogSqlStatus(bool $status = false): self
    {
        $this->autoLogSqlStatus = $status;
        return $this;
    }

    /**
     * 获取数据模型
     *
     * @param string $suffixName
     * @return string|Model
     *
     * @author wlq
     * @since 1.0 20210429
     */
    protected function getModel(string $suffixName = '')
    {
        $modelName = $this->modelName . $suffixName;
        return '\\app\model\\' . $modelName;
    }


    /**
     * 主键获取数据
     *
     * @param $id
     * @param string $modelName
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getById($id, string $modelName = ''): array
    {
        $modelPath = $this->getModel($modelName);
        $data = $modelPath::find($id);
        return $data ? $data->toArray() : [];
    }

    /**
     * 查询条件查询数据
     * <br>(($fields = '*' & $key = ‘’) || $key = null)，则返回以主键为键名的数组
     * <br>($fields != '*' & $key != null')，则返回以$key为键名的数组【$key=''时，返回有序数组】
     *
     * @param null $where
     * @param string $fields
     * @param string|null $key
     * @param string $modelName
     * @return array
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getWhereByKey(
        $where = null,
        string $fields = '*',
        string $key = null,
        string $modelName = ''
    ): array {
        $modelPath = $this->getModel($modelName);
        $model = $modelPath::where($where);
        $key = $key === null ? $model->getPk() : $key;
        return $model->column($fields, $key);
    }

    /**
     * 查询条件获取单条数据
     *
     * @param null $where
     * @param string $order
     * @param string $modelName
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210608
     */
    public function findWhere($where = null, string $order = '', string $modelName = ''): array
    {
        $modelPath = $this->getModel($modelName);
        $data = $modelPath::where($where)->order($order)->find();
        return $data ? $data->toArray() : [];
    }
    /**
     * 查询条件单表分页查询
     *
     * @param null $where
     * @param int $page
     * @param int $size
     * @param string $fields
     * @param string $order
     * @param string $modelName
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getPage(
        $where = null,
        int $page = 1,
        int $size = 1,
        string $fields = '*',
        string $order = '',
        string $modelName = ''
    ): array {
        $modelPath = $this->getModel($modelName);
        $model = $modelPath::where($where);
        $data = $model->field($fields)->order($order)->page($page, $size)->select()->toArray();
        $count = $modelPath::where($where)->count($model->getPk());
        return $this->setPageData($data, $page, $size, $count);
    }

    /**
     * 分页数据整理格式
     *
     * @param $rows
     * @param $page
     * @param $size
     * @param $count
     * @return array
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function setPageData($rows, $page, $size, $count): array
    {
        return [
            'rows' => $rows,
            'total' => $count,
            'next' => $page * $size >= $count ? 0 : 1,
            'page' => $page,
            'size' => $size
        ];
    }
    /**
     * 批量主键获取数据
     *
     * @param array $ids
     * @param string $fields
     * @param string $modelName
     * @return mixed
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getByIds(array $ids, string $fields = '*', string $modelName = '')
    {
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $pk = $model->getPk();
        return $model->where($pk, 'in', $ids)->column($fields, $pk);
    }

    /**
     * 单条新增数据
     * <br>新增数据默认使用post请求参数
     *
     * @param array $data
     * @param string $modelName
     * @return mixed
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function insertOne(array $data = [], string $modelName = '')
    {
        $data = $data ?: app()->request->post();
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $model->save($data);
        $pk = $model->getPk();
        return $model->$pk;
    }

    /**
     * 批量添加数据
     * <br>新增数据默认使用post请求参数insertAll数据集合
     *
     * @param array $data
     * @param string $modelName
     * @return bool
     *
     * @author wlq
     * @since 1.0 20210428
     */
    public function insertAll(array $data = [], string $modelName = ''): bool
    {
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $filedType = $model->getOptions()['field_type'];
        //参数校验
        $data = $this->makeAllData($filedType, $data);
        $model->insertAll($data);
        return true;
    }

    /**
     * 生成批量新增数据
     * <br>新增数据默认使用post请求参数insertAll数据集合
     * <br>自动过滤非数据表数据
     * <br>自动填充为提交字段默认值
     *
     * @param $filedType
     * @param array|null $data
     * @param array $autoField
     * @return array
     *
     * @author wlq
     * @since 1.0 20210428
     */
    public function makeAllData($filedType, array $data = [], array $autoField = []): array
    {
        $data = $data ?: app()->request->post('insert_all');
        $insertAll = [];
        //字段类型默认值规则
        $typeDefaultVal = ['int' => 0, 'float' => 0, 'string' => ''];
        //自动填充字段
        $time = time();
        isset($filedType['create_time']) and $autoField['create_time'] = $time;
        isset($filedType['update_time']) and $autoField['update_time'] = $time;
        foreach ($data as $value) {
            $save = [];
            foreach ($filedType as $field => $type) {
                $save[$field] = $value[$field] ?? ($autoField[$field] ?? $typeDefaultVal[$type]);
            }
            $insertAll[] = $save;
        }
        return $insertAll;
    }

    /**
     * 主键修改数据-单个
     *
     * @param $id
     * @param array $data
     * @param string $modelName
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function updateById($id, array $data = [], bool $checkEmpty = false, string $modelName = ''): void
    {
        $modelPath = $this->getModel($modelName);
        $data = $data ?: (app()->request->put() ?: app()->request->post());
        if ($checkEmpty) {
            $model = $modelPath::find($id);
            if (!$model) {
                throw new DataNotFoundException('更新失败：数据不存在或已删除');
            }
        } else {
            $model = new $modelPath();
            $pk = $model->getPk();
            $model = $model->where($pk, $id);
        }
        $model->save($data);
    }

    /**
     * 主键修改数据-批量
     *
     * @param array$ids
     * @param array $data
     * @param string $key
     * @param string $modelName
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function updateByIds(array $ids, array $data = [], string $key = '', string $modelName = ''): void
    {
        $data = $data ?: (app()->request->put() ?: app()->request->post());
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $pk = $key ?: $model->getPk();
        $model->where($pk, 'in', $ids)->data($data)->save();
    }
    /**
     * 主键删除数据
     *
     * @param array $ids
     * @param string $modelName
     * @return bool
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function delByIds(array $ids, string $modelName = ''): void
    {
        $ids = $ids ?: app()->request->delete('ids');
        if ($ids) {
            !is_array($ids) and $ids = explode(',', $ids);
            $modelPath = $this->getModel($modelName);
            $modelPath::destroy($ids);
        }
    }

    /**
     * 若开启自动记录sql，则函数执行结束时自动记录sql
     */
    public function __destruct()
    {
        if ($this->autoLogSqlStatus) {
            LibBaseLog::get()->mysql->autoSetLog();
        }
    }
}