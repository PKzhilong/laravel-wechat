<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/6/30
 * Time: 下午8:33
 */

namespace Laravel\WeChatPay;


use EasyWeChat\Foundation\Application;

class WeChatPay
{
    public $application;
    public $payment;
    private $config;
    public $tradeType;
    public $prepayId;
    public $payMethodObj;
    public $codeUrl;
    public $outRefundNo;

    public function bootstrapWeChat(array $config, string $tradeType = 'NATIVE')
    {

        $this->config = $config;
        $this->tradeType = $tradeType;
        $this->application = new Application($this->config);
        $this->payment = $this->application->payment;
        $this->payMethodObj = WeChatFactory::getInstance($this->tradeType);
        return $this;
    }

    /**
     * 生成订单
     * @param array $orderData
     * @return $this
     */
    public function generateOrder(array $orderData)
    {
        $this->payMethodObj->orderMake($orderData);
        return $this;
    }

    /**
     * 统一下订单
     * @return $this
     */
    public function prepareOrder()
    {
      $result = $this->payment->prepare($this->payMethodObj->order);
      if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
          $this->prepayId = $result->prepay_id;
          if ($this->tradeType == 'NATIVE') $this->codeUrl = $result->code_url;
      }
      return $this;
    }

    public function queryOrder($outTradeNo)
    {
        $result = $this->payment->query($outTradeNo);
        return $result;
    }

    /**
     * 申请退款
     * @param int $transactionId
     * @param int $cost
     * @return mixed
     */
    public function refundByTransactionId($transactionId, $cost)
    {
        $this->outRefundNo = $this->payMethodObj->generateOutTradeNo(32);
        $result = $this->payment->refundByTransactionId($transactionId, $this->outRefundNo, $cost);
        return $result;
    }

    /**
     * 获得签名
     * @param $notify
     * @return string
     */
    public function makeSign($notify)
    {

        //签名步骤一：按字典序排序数组参数
        $params = $this->makeSignString($notify);
        ksort($params);
        //签名步骤二：在string后加入KEY
        $params['key'] = $this->config['payment']['key'];
        $string = http_build_query($params);


        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 生成签名所需要的数组
     * @param $notify
     * @return array
     */
    public function makeSignString($notify)
    {
        $w_sign = [];           //参加验签签名的参数数组

        foreach ($notify as $key => $item) {
            if ($key != 'sign') {
                $w_sign[$key] = $item;
            }
        }
        return $w_sign;
    }

}