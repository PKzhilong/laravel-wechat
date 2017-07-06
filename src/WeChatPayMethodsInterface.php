<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/7/1
 * Time: 下午5:12
 */

namespace Laravel\WeChatPay;


interface WeChatPayMethodsInterface
{
    public function generateOutTradeNo(int $length); //生成订单号
    public function makeAttributed(array $orderData); //组合订数组
    public function orderMake(array $orderData); //生成订单

}