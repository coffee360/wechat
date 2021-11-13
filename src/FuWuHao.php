<?php

namespace Phpfunction\Wechat;

use EasyWeChat\Factory;
use Phpfunction\App\HttpApp;

/**
 * 微信公众号——服务号
 * Class Open
 * @package Phpfunction\Wechat
 */
class FuWuHao
{

    protected $app_id = "";
    protected $secret = "";
    protected $token  = '';

    protected $app = null;


    public function __construct($app_id, $secret, $token)
    {
        $this->app_id = $app_id;
        $this->secret = $secret;
        $this->token  = $token;
        $config       = [
            'app_id' => $this->app_id,
            'secret' => $this->secret,
            'token'  => $this->token,
        ];
        $this->app    = Factory::officialAccount($config);
    }


    /**
     * 服务号app
     */
    public function getApp()
    {
        return $this->app;
    }


    /**
     * 获得用户详情
     */
    public function getUserInfoByOpenid($openid)
    {
        return $this->app->user->get($openid);
    }


    /**
     * 临时二维码
     */
    public function getQrcodeTmp($sceneValue, $expireSeconds = 60 * 5)
    {
        $qrcode = $this->getApp()->qrcode->temporary($sceneValue, $expireSeconds);

        return $this->getApp()->qrcode->url($qrcode['ticket']);
    }


    /**
     * 模板消息
     */
    public function messageSend($openid = '', $template_id = '', $data = [], $url = '')
    {
        return $this->app->template_message->send([
            'touser'      => $openid,
            'template_id' => $template_id,
            'data'        => $data,
            'url'         => $url,
        ]);
    }

    // ###################################################################################
    // ###################################################################################
    // ###################################################################################

    /**
     * 由code获得access_token
     * @param $code
     * @return bool|string
     */
    public function getAccessToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?";
        $url .= "appid=" . $this->app_id . "&secret=" . $this->secret;
        $url .= "&code=" . $code . "&grant_type=authorization_code";

        return (new HttpApp())->get($url);
    }


}
