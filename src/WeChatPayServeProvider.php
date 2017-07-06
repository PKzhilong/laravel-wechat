<?php

namespace Laravel\WeChatPay;

use Illuminate\Support\ServiceProvider;

class WeChatPayServeProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('wechatpay', function(){
            return new WeChatPay();
        });
        $this->mergeConfigFrom(
            __DIR__ . '/config/wechat_pay.php', 'wechat_pay'
        );
    }

    public function provides()
    {
        return [WeChatPay::class];
    }
}
