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

use TpDataDocking\Operation;
/**
 * @property \app\operation\face\Api $api
 */
class Docking extends Operation
{
    private $namespaceBase = "\\app\\operation\\";
}
```

- 门面类定义
```php
namespace app\operation\face;

use TpDataDocking\Helper\Face;
/**
 * @property \app\operation\api\User $user
 */
class Api
{
    use Face;
}
```

- 数据服务类定义
```php
namespace app\operation\api;

use TpDataDocking\Helper\Api;

class User
{
    use Api;
    public function apiRequest($params)
    {
        $res = $this->client->get('apiPath', $params);
        $this->throwIfError($res, '请求失败');
        return $res->getJsonBody(true);
    }
}
```
- 业务类调用数据服务
```php
namespace app\core;

use app\helper\Docking;
/**
 * @property \app\operation\face\Api $api
 */
class Core
{
    public static function aaa()
    {
        Docking::init()->api->user->apiRequest([]);
    }
}
```
