该文档中涉及到代码为基础调用演示，具体接口返回参数各不相同，请到官方网站查看详细文档。
官方直达： https://pay.kktt.cn

## **特点**

* 轻量级SDK，仅一个PHP文件
* 统一接口，使用起来不复杂
* 根据支付宝、微信最新API开发而成
* 已封装请求，仅需几个业务参数就能发起支付
* 可扩展更多接口，不影响原有代码开发

## **运行环境**

*   PHP >= 7.1
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
| wechat.trade.wap | H5支付 |
### 公共方法
| method | 接口名称 |
| :-: | :-: |
| trade.query | 统一订单查询 |

##   **安装**
~~~
composer require kitesky/mzhpay
~~~
##   **使用说明**
### 两行代码实现接入
~~~
$order = ['method' => 'trade.query','title'  => '测试标题001','amount' => 1,'out_order_no' => 'P2020101231'];
$pay->sendPost($order);
~~~

### 完成接入代码示例
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
            'method' => 'trade.query', // 接口名称
            'title'  => '测试标题001',  // 产品标题
            'amount' => 1,             // 订单金额
            'out_order_no' => 'ZF2021012314301111', // 商户订单号
            'extra' => json_encode(['a' => 1112,'bbb' => 'fsafas']), // 额外参数，原样返回
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
具体返回参数，请查看官方开发文档。


##   许可协议
MIT协议
