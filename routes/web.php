<?php

use Encore\QiniuUpload\Http\Controllers\QiniuUploadController;

Route::any('qiniu-upload/getToken', QiniuUploadController::class.'@getToken');
//
Route::get('qiniu-upload/file_exists', QiniuUploadController::class.'@file_exists')->name('qiniu-upload.file_exists');
