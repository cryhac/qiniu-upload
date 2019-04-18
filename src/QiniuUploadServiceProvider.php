<?php

namespace Encore\QiniuUpload;

use Illuminate\Support\ServiceProvider;
use Cookie;
use Encore\Admin\Facades\Admin;

class QiniuUploadServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(QiniuUpload $extension)
    {
        if (! QiniuUpload::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'qiniu-upload');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/qiniu-upload')],
                'qiniu-upload'
            );
        }
        $this->app->booted(function () {
            QiniuUpload::routes(__DIR__.'/../routes/web.php');
        });
        Admin::booting(function (){
            Admin::js('https://unpkg.com/qiniu-js@2.5.4/dist/qiniu.min.js');
            Admin::js('https://cdn.staticfile.org/blueimp-md5/2.10.0/js/md5.min.js');
        });
    }
}