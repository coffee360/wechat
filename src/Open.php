<?php

namespace Phpfunction\Wechat;

use EasyWeChat\Factory;
use Phpfunction\App\HttpApp;

/**
 * 微信开放平台
 * Class Open
 * @package Phpfunction\Wechat
 */
class Open
{
    protected $app_id  = "";
    protected $secret  = "";
    protected $token   = "";
    protected $aes_key = "";

    protected $app = null;


    public function __construct($app_id, $secret, $token, $aes_key)
    {
        $this->app_id  = $app_id;
        $this->secret  = $secret;
        $this->token   = $token;
        $this->aes_key = $aes_key;
        $config        = [
            'app_id'  => $this->app_id,
            'secret'  => $this->secret,
            'token'   => $this->token,
            'aes_key' => $this->aes_key,
        ];
        $this->app     = Factory::openPlatform($config);
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
