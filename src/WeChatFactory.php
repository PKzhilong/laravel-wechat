<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/7/1
 * Time: 上午8:58
 */

namespace Laravel\WeChatPay;


use Laravel\WeChatPay\PayMethods\WeChatPayScan;

class WeChatFactory
{
    private $factoryObj;

    public static function getInstance(string $tradeType)
    {
        switch ($tradeType) {
            case 'JSAPI';
                break;
            case 'NATIVE';
                return new WeChatPayScan();
                break;
            case 'APP';
                break;
        }
    }
}