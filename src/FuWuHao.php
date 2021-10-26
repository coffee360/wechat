<?php

namespace Phpfunction\Wechat;

use Phpfunction\App\HttpApp;

/**
 * 微信公众号——服务号
 * Class Open
 * @package Phpfunction\Wechat
 */
class FuWuHao
{

    public $app_id = "";
    public $secret = "";


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
