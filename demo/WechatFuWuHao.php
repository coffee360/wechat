<?php

namespace app\model;

use Phpfunction\Wechat\FuWuHao;

class WechatFuWuHao extends FuWuHao
{
    public function __construct()
    {
        parent::__construct(
            config('wechat.fuwuhao.AppID'),
            config('wechat.fuwuhao.AppSecret'),
            config('wechat.fuwuhao.token')
        );
    }

}