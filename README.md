该文档为基础调用演示，具体接口返回参数各不相同，请到官方网站查看详细文档。
官方直达： https://pay.kktt.cn

## **特点**

* 轻量级仅仅使用一个PHP文件
* 根据支付宝、微信最新 API 开发而成
* 统一接口，使用起来不复杂
* 已封装请求，仅需几个业务参数就能发起支付
* 可扩展更多接口，不影响原有代码开发

## **运行环境**

*   PHP >= 5.6.31
*   composer

## **已支持三方支付**
### 支付宝
| method | 接口名称 |
| :-: | :-: |
| alipay.trade.web | 电脑PC支付 |
| alipay.trade.wap | 手机网站支付 |
| alipay.trade.scan | 扫码支付 |
### 微信
| method | 接口名称 |
| :-: | :-: |
| wechat.trade.scan | 扫码支付 |
### 公共方法
| method | 接口名称 |
| :-: | :-: |
| trade.query | 统一订单查询 |

##   **安装**
~~~
composer require kite/mzh-pay
~~~
##   **使用说明**
### 支付宝电脑支付代码
~~~
<?php
namespace app\controller

use payment\simplePay;

class Test
{
   // 发起请求
    public function sendPost()
    {
        $pay = new simplePay;

        $order = [
            'method' => 'trade.query',
            'title'  => '测试标题001',
            'amount' => 1,
            'out_order_no' => 'ZF2021012314301111',
            'extra' => json_encode(['a' => 1112,'bbb' => 'fsafas']),
        ];
        try {
            $res = $pay->sendPost($order);
            print_r($res);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }

    }

   // 验证签名
    public function verify()
    {
        $pay = new simplePay;
        try {
            $res = $pay->verify();
            print_r($res);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
}


~~~

##   许可协议
MIT协议
