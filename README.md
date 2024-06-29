# tp-data-docking
对接集中处理数据服务，以解决单测无法mock静态调用数据服务类问题

## 运行环境
- PHP 7.2+
- curl extension

## 安装方法
1. 根目录运行

        composer require wlqacym/tp-data-docking

2. 在`composer.json`中声明

        "require": {
            "aichenk/http-client": "^2.0",
            "topthink/think-orm": "^2.0",
            "topthink/framework": "^6.0"
        }



## 使用
- 数据对接类定义(若不定义，使用Operation对接基类，不影响使用，但无IDE提示)

```php
namespace app\helper;

use tpDataDocking\helper\Operation;
use app\operation\face\Db;
use app\operation\face\Api;

class Docking extends Operation
{
    /**
     * @var \app\operation\face\Db $db
     */
    protected static $db;
    /**
     * @var \app\operation\face\Api $api
     */
    protected static $api;
    /**
     * setDbFace 实例化db门面类
     *
     * @return Db
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    protected static function setDbFace() {
        return self::db = new Db();
    }
    /**
     * setApiFace 实例化Api门面类
     *
     * @return Api
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    protected static function setApiFace() {
        return self::db = new Api();
    }
}
```

- 门面类定义
```php
namespace app\operation\face;

use tpDataDocking\helper\Face;
use app\operation\api\User;
/**
 * @property User user()    通过定义属性方式添加三方服务类实例,增加实例ide方法提示
 */
class Api extends Face
{
    /**
     * 通过定义属性方式添加三方服务类实例，需通过类的@property注释添加ide方法提示
     * @var \class-string[] 
     */
    protected $classArr = [
        'user' => User::class,
    ];
    /**
     * @var array 实例化类存储列表
     */
    protected $classService = [];
    /**
     * user 通过定义方法方式获取三方服务类实例
     *
     * @return User
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    public function user(){
        if (!isset($this->classService['user'])) {
            $this->classService['user'] = new User();
        }
        return $this->classService['user'];
    }
}


use app\operation\db\Order;

/**
 * @property Order order()    通过定义属性方式添加数据库操作实例,增加实例ide方法提示
 */
class Db extends Face
{
    /**
     * 通过定义属性方式添加数据库操作实例，需通过类的@property注释添加ide方法提示
     * @var \class-string[] 
     */
    protected $classArr = [
        'order' => Order::class,
    ];
    /**
     * @var array 实例化类存储列表
     */
    protected $classService = [];
    /**
     * order 通过定义方法方式获取数据库操作实例
     *
     * @return Order
     *
     * @author wlq
     *
     * @since 1.0 2024-06-22
     */
    public function order(){
        if (!isset($this->classService['order'])) {
            $this->classService['order'] = new Order();
        }
        return $this->classService['order'];
    }
}
```

- 数据服务类定义
```php
namespace app\operation\api;

use tpDataDocking\helper\Api;

class User extends Api
{
    protected $name = 'user';
    public function apiRequest($params)
    {
        $res = $this->client->get('apiPath', $params);
        $this->throwIfError($res, '请求失败');
        return $res->getJsonBody(true);
    }
}


namespace app\operation\db;

use tpDataDocking\helper\Db;

class Order extends Db
{
    protected $modelName = 'order';
    public function getPageList(int $page, int $size)
    {
        $modelPath = $this->getModel('suffix');
        return $modelPath::where('')->select()->toArray();
    }
}
```
- 业务类调用数据服务
```php
namespace app\core;

use app\helper\Docking;
class Core
{
    public static function aaa()
    {
        Docking::api()->user()->apiRequest([]);
        Docking::db()->order()->getById(1);
    }
}
```