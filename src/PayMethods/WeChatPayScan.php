<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/7/1
 * Time: 上午9:03
 */

namespace Laravel\WeChatPay\PayMethods;


use EasyWeChat\Payment\Order;
use Laravel\WeChatPay\WeChatPayMethodsInterface;

class WeChatPayScan implements WeChatPayMethodsInterface
{
    public $order;
    public $outTradeNo;
    public $orderLength;

    public function __construct()
    {
        $this->orderLength = config('wechat_pay.order_length');
    }


    /**
     * 生成订单
     * @param array $orderData
     * @return $this
     */
    public function orderMake(array $orderData)
    {
        $data = $this->makeAttributed($orderData);
        $this->order = new Order($data);
        return $this;
    }

    /**
     * 组合生成订单数组
     * @param array $orderData
     * @return array
     */
    public function makeAttributed(array $orderData)
    {
        $this->outTradeNo = $this->generateOutTradeNo($this->orderLength);
        $attributes = [
            'trade_type' => 'NATIVE',
            'out_trade_no' => $this->outTradeNo
        ];
        return $attributes = array_merge($attributes, $orderData);
    }

    /**
     * 随机生成指定长度字符串
     * @param int $length
     * @return string
     */
    public function generateOutTradeNo(int $length)
    {
        $range = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        $orderNum = '';
        while ($length) {
            $orderNum .= $range[mt_rand(0, 61)];
            $length--;
        }
        return $orderNum;
    }

}