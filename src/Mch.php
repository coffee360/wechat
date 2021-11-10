<?php

namespace Phpfunction\Wechat;

use chillerlan\QRCode\QRCode;
use EasyWeChat\Factory;

/**
 * 微信公众号——商户号
 * Class Open
 * @package Phpfunction\Wechat
 */
class Mch
{

    protected $app_id     = "";
    protected $mch_id     = "";
    protected $key        = "";
    protected $cert_path  = "";
    protected $key_path   = "";
    protected $notify_url = "";

    protected $app = null;


    /**
     * Mch constructor.
     * @param $app_id           appid
     * @param $mch_id
     * @param $key              API密钥
     * @param $cert_path        绝对路径
     * @param $key_path         绝对路径
     */
    public function __construct($app_id, $mch_id, $key, $cert_path, $key_path, $notify_url = '')
    {
        $this->app_id     = $app_id;
        $this->mch_id     = $mch_id;
        $this->key        = $key;
        $this->cert_path  = $cert_path;
        $this->key_path   = $key_path;
        $this->notify_url = $notify_url;

        $config = [
            'app_id'     => $this->app_id,
            'mch_id'     => $this->mch_id,
            'key'        => $this->key,
            'cert_path'  => $this->cert_path,
            'key_path'   => $this->key_path,
            'notify_url' => $this->notify_url,
        ];

        $this->app = Factory::payment($config);
    }


    public function getApp()
    {
        return $this->app;
    }


    /**
     * 商品支付，生成商品二维码
     */
    public function unifyProductQrcodeContent($product_id)
    {
        $content = $this->getApp()
            ->scheme($product_id);

        return $content;
    }


    /**
     * 商品支付，生成商品二维码
     */
    public function unifyProductQrcodeImg($product_id, $file_name = "")
    {
        $content = $this->unifyProductQrcodeContent($product_id);

        $res = [
            'data' => $content
        ];
        if ($file_name) {
            (new QRCode())->render($content, $file_name);
            $res['img'] = $file_name;
        } else {
            $res['img'] = (new QRCode())->render($content);
        }

        return $res;
    }


    /**
     * 订单支付，生成二维码
     */
    public function unifyOrderQrcode($openid, $out_trade_no, $total_fee, $notify_url, $body = '', $file_name = '')
    {
        $result = $this->unifyOrder($openid, $out_trade_no, $total_fee, $notify_url, $body, 'NATIVE');

        if (!empty($result['result_code']) && !empty($result['return_code'])) {
            if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
                $code_url = $result['code_url'];

                $res = [
                    'code'     => 0,
                    'code_url' => $code_url,
                ];
                if ($file_name) {
                    (new QRCode())->render($code_url, $file_name);
                    $res['img'] = $file_name;
                } else {
                    $res['img'] = (new QRCode())->render($code_url);
                }

                return $res;
            }
        }

        return $result + ['code' => 1];
    }


    /**
     * 订单支付，弹框输入密码
     */
    public function unifyOrderAlert($openid, $out_trade_no, $total_fee, $notify_url, $body = '')
    {
        $result = $this->unifyOrder($openid, $out_trade_no, $total_fee, $notify_url, $body);

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
    public function unifyOrder($openid, $out_trade_no, $total_fee, $notify_url, $body = '', $trade_type = 'JSAPI')
    {
        $arr = [
            'openid'       => $openid,
            'out_trade_no' => $out_trade_no,
            'total_fee'    => $total_fee * 100,

            // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'notify_url'   => $notify_url,

            'body'       => $body,

            // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            //            'spbill_create_ip' => '123.12.12.123',

            // 请对应换成你的支付方式对应的值类型,JSAPI=弹框,NATIVE=生成二维码
            'trade_type' => $trade_type,

        ];

        if ("NATIVE" == $trade_type) {
            $arr['product_id'] = $trade_type;
        }

        // 参数
        return $this->app->order->unify($arr);
    }


}



