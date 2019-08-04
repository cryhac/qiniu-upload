<?php
namespace Encore\QiniuUpload\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Encore\QiniuUpload\Http\Models\Attachment;
use Encore\Admin\Facades\Admin;


class QiniuUploadController extends Controller
{
	public $accessKey;
    public $secretKey;
    public $bucket;
    public $domain;
    public function __construct()
    {

    	$this->accessKey = env('QINIU_ACCESS_KEY', '');
    	$this->secretKey = env('QINIU_SECRET_KEY', '');
    	$this->bucket = env('QINIU_BUCKET', '');
    	$this->domain = env('QINIU_DOMAIN', '');
    }
    
    public function getToken(Request $request)
    {
    	$upManager = new UploadManager();
    	$auth = new Auth($this->accessKey, $this->secretKey);
    	return ['token'=> $auth->uploadToken($this->bucket), 'domain'=> $this->domain];
    }

    public function file_exists(Request $request)
    {
        return ['status'=> '0'];
    }

}
