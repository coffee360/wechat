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

    private $app_id = "";
    private $secret = "";
    private $token  = '';

    private $app = null;


    public function __construct($app_id, $secret, $token)
    {
        $this->app_id = $app_id;
        $this->secret = $secret;
        $this->secret = $token;
        $config       = [
            'app_id' => $this->app_id,
            'secret' => $this->secret,
            'token'  => $this->token,
        ];
        $this->app    = Factory::officialAccount($config);
    }


    public function messageSend($openid = '', $template_id = '', $data = [])
    {
        return $this->app->template_message->send([
            'touser'      => $openid,
            'template_id' => $template_id,
            'data'        => $data,
        ]);
    }


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


    /**
     * 用户详情
     */
    public function getUserinfo($access_token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?";
        $url .= "access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";

        return (new HttpApp())->get($url);
    }

}
