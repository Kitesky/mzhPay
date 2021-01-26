<?php
/**
 * 轻量聚合支付SDK
 * 开发人员: 风筝<wangzheng@kktt.cn>
 * 开发公司：成都美智合科技有限公司
 * 官方网址：https://www.kktt.cn
 * 完成日期：2021-01-26
 */
namespace payment;

class simplePay
{
    // 请求网关地址
    private $url = 'https://pay.kktt.cn/pay/gateway';

    // 请求接口基础参数
    private $config = [   
        'merchant_id' => 100001,                            // 商户ID, 还没有账户请去平台(https://pay.kktt.cn)申请
        'secret_key'  => '11111111111111',                  // 商户密钥
        'sign_type'   => 'md5',                             // 签名方式 仅支持MD5
        'version'     => '1.0',                             // 版本号 固定1.0
        'charset'     => 'utf-8',                           // 编码方式
        'format'      => 'json',                            // 数据格式 仅支持json
        'return_url'  => '',                                // 同步通知跳转地址
        'notify_url'  => '',                                // 异步通知地址
    ];

    // 错误信息
    protected $error = '';

    public function __get($name){
        if(isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }

    public function __set($name, $value){
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    public function __isset($name){
        return isset($this->config[$name]);
    }

    /**
     * 构造函数 合并配置信息
     * @access public
     * @param array $config  商户配置信息
     */
    public function __construct($config = []) {
        if(is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    public function sendPost($order)
    {
        //1.发送之前检测 商户ID，密钥，gateway
        if ($this->checkEmpty($this->config['merchant_id']) || $this->checkEmpty($this->config['secret_key'])) {
            throw new \Exception('商户信息错误，请检查配置项');
        }
        //2.拼装参数
        $params = array_merge($this->config, $order);
        //3.去空
        $postData = [];
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (!$this->checkEmpty($value)) {
                    $postData[$key] = $value;
                }
            }
        }
        // 4.签名
        $postData['sign'] = $this->getSign($postData);
        // 5.发送请求
        return $this->curl($this->url, $postData);
    }

    /**
     * 接收异步通知消息验签
     * @access public
     * @param array $params 需要签名的数据
     * @return array
     */
    public function verify() {
        // 接收参数
        $params = $_POST;
        if (!isset($params['sign'])) {
            throw new \Exception('签名sign字段内容不存在');
        }
        if ($this->checkEmpty($params['sign'])) {
            throw new \Exception('签名sign字段内容不存在');
        }
        // 卸载签名 
        $params['secret_key'] = $this->config['secret_key'];
        $sign = $this->getSign($params); //299e2c61eaa02aaf55dee9cd214b3cf9 299e2c61eaa02aaf55dee9cd214b3cf9
        // 对比签名
        if ($sign == $params['sign']) {
            return $params;
        } else {
            throw new \Exception('验签失败');
        }
    }

    /**
     * 签名函数
     * @access private
     * @param array $params 需要签名的数据
     * @return string
     */
    protected function getSign($params) {
        // 卸载数据中的sign ，sign不参与签名
        if (isset($params['sign'])) {
            unset($params['sign']);
        }
        // 第一步 对数组的值按key排序
        ksort($params);
        // 第二步 生成url的形式(merchant_id=1&secret_key=1111111&out_order_no=H2021012612345)
        $str = http_build_query($params);
        // 第三步 生成sign
        $sign = md5($str);
        return $sign;
    }

	/**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *    if is null , return true;
	 **/
	protected function checkEmpty($value) {
        if (is_array($value) || is_object($value)) {
            throw new \Exception('参数格式不正确，不能是数组或者对象');
        }
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;

		return false;
	}

	protected function curl($url, $postFields = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // $headers = array('content-type: application/x-www-form-urlencoded;charset=utf-8');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, $headers);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		$reponse = curl_exec($ch);
		if (curl_errno($ch)) {
			throw new \Exception(curl_error($ch), 0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new \Exception($reponse, $httpStatusCode);
			}
		}

        curl_close($ch);
		return json_decode($reponse, true);
	}
}