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


    public $openid = '';

    protected $msg_template_id = "";
    public    $msg_first       = "";
    public    $msg_remark      = "";
    public    $msg_keyword1    = "";
    public    $msg_keyword2    = "";
    public    $msg_keyword3    = "";
    public    $msg_keyword4    = "";
    public    $msg_keyword5    = "";
    public    $msg_keyword6    = "";
    public    $msg_keyword7    = "";
    public    $msg_keyword8    = "";


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
