<?php

namespace Phpfunction\Wechat;

use EasyWeChat\Factory;

/**
 * 微信公众号——商户号
 * Class Open
 * @package Phpfunction\Wechat
 */
class Mch
{

    protected $app_id    = "";
    protected $mch_id    = "";
    protected $key       = "";
    protected $cert_path = "";
    protected $key_path  = "";

    protected $app = null;


    /**
     * Mch constructor.
     * @param $app_id           appid
     * @param $mch_id
     * @param $key              API密钥
     * @param $cert_path        绝对路径
     * @param $key_path         绝对路径
     */
    public function __construct($app_id, $mch_id, $key, $cert_path, $key_path)
    {
        $this->app_id    = $app_id;
        $this->mch_id    = $mch_id;
        $this->key       = $key;
        $this->cert_path = $cert_path;
        $this->key_path  = $key_path;

        $config = [
            'app_id'    => $this->app_id,
            'mch_id'    => $this->mch_id,
            'key'       => $this->key,
            'cert_path' => $this->cert_path,
            'key_path'  => $this->key_path,
        ];

        $this->app = Factory::payment($config);
    }


    public function getApp()
    {
        return $this->app;
    }


    /**
     * 支付弹输入密码
     */
    public function unifyAlert($out_trade_no, $total_fee, $notify_url, $openid, $body)
    {
        $result = self::unifyOrder($out_trade_no, $total_fee, $notify_url, $openid, $body);

        if (!empty($result['result_code']) && !empty($result['return_code'])) {
            if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
                $prepay_id           = $result['prepay_id'];
                $jssdk               = $this->getApp()->jssdk;
                $config              = $jssdk->sdkConfig($prepay_id);
                $config['timeStamp'] = $config['timestamp'];//此处需要将小写s变为大写

                return $config;
            }
        }

        return $result;
    }


    /**
     * 支付下单
     */
    public function unifyOrder($out_trade_no, $total_fee, $notify_url, $openid, $body)
    {
        $arr = [
            'body'         => $body,
            'out_trade_no' => $out_trade_no,
            'total_fee'    => $total_fee * 100,

            // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            //            'spbill_create_ip' => '123.12.12.123',

            // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'notify_url'   => $notify_url,

            // 请对应换成你的支付方式对应的值类型
            'trade_type'   => 'JSAPI',

            'openid' => $openid
        ];

        // 参数
        return $this->app->order->unify($arr);
    }


}



