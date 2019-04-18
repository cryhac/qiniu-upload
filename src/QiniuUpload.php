<?php

namespace Encore\QiniuUpload;

use Encore\Admin\Extension;

class QiniuUpload extends Extension
{
    public $name = 'qiniu-upload';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => '七牛云-大文件上传',
        'path'  => 'qiniu-upload',
        'icon'  => 'fa-cloud-upload',
    ];
    
}