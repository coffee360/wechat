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
     *  'miniprogram' => [
     * 'appid' => 'xxxxxxx',
     * 'pagepath' => 'pages/xxx',
     * ],
     */
    public function messageSend($openid = '', $template_id = '', $data = [], $url = '', $miniprogram = [])
    {
        $msg = [
            'touser'      => $openid,
            'template_id' => $template_id,
            'data'        => $data,
        ];
        if (!empty($url)) {
            $msg['url'] = $url;
        }
        if (!empty($miniprogram)) {
            $msg['miniprogram'] = $miniprogram;
        }

        return $this->app->template_message->send($msg);
    }

    // ###################################################################################
    // ###################################################################################
    // ###################################################################################

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

    public $url = "";

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
