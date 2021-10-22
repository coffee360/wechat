<?php

namespace Phpfunction\Wechat;

use Phpfunction\App\HttpApp;

/**
 * 微信开放平台
 * Class Open
 * @package Phpfunction\Wechat
 */
class Open
{
    public $app_id  = "";
    public $secret  = "";
    public $token   = "";
    public $aes_key = "";


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
