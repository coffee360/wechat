<?php

namespace Phpfunction\Wechat;

use EasyWeChat\Factory;
use Phpfunction\App\HttpApp;

/**
 * 微信公众号——小程序
 * Class Open
 * @package Phpfunction\Wechat
 */
class Program
{

    protected $app_id = "";
    protected $secret = "";

    protected $app = null;


    public function __construct($app_id, $secret)
    {
        $this->app_id = $app_id;
        $this->secret = $secret;
        $config       = [
            'app_id' => $this->app_id,
            'secret' => $this->secret,
        ];

        $this->app = Factory::miniProgram($config);
    }


    /**
     * 服务号app
     */
    public function getApp()
    {
        return $this->app;
    }


    /**
     * 由code获得access_token
     * @param $code
     * @return bool|string
     */
    public function getAccessToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?";
        $url .= "appid=" . $this->app_id . "&secret=" . $this->secret;
        $url .= "&js_code=" . $code . "&grant_type=authorization_code";

        return (new HttpApp())->get($url);
    }


}
