<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2020/3/6
 * Time: 16:06
 */
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

Class WechatPay extends Controller{
    protected $config = [

            'app_id' => 'wx66887e31db34218d',//微信公众号appid
            'mch_id' => '1526730191',
            'key' => 'YAXIAOBAI12313YASIDysxiaobai1323',//商户秘钥
            'cert_client' => './apiclient_cert.pem',
            'cert_key' => './apiclient_key.pem',
            'notify_url' => 'https://hn.dwbsu.com/home/wechat_pay/notify',


    ];

    //微信支付
    public function index($order_sn,$amount)
    {
        $config_biz = [
            'out_trade_no' => $order_sn,
            'total_fee' => $amount,
            'body' => '测试',
            'spbill_create_ip' => $_SERVER["REMOTE_ADDR"],

        ];

        return Pay::wechat($this->config)->wap($config_biz);

    }
    //支付回调
    public function notify()
    {
        $pay = Pay::wechat($this->config);

        try{
            $data = $pay->verify(); // 是的，验签就这么简单！

            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $pay->success()->send();// laravel 框架中请直接 `return $pay->success()`
    }

    //微信退款
    public function refund($order){
        $pay = Pay::wechat($this->config)->refund($order);
    }
}