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


}
