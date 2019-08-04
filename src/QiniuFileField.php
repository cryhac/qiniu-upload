<?php

namespace Encore\QiniuUpload;

use Encore\Admin\Form\Field;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Illuminate\Support\Facades\Storage;


class QiniuFileField extends Field
{
    public $view = 'qiniu-upload::qiniu_file_upload';
    public $accessKey;
    public $secretKey;
    public $bucket;
    public $domain;
    
    public function render()
    {
        $this->accessKey = env('QINIU_ACCESS_KEY', '');
        $this->secretKey = env('QINIU_SECRET_KEY', '');
        $this->bucket = env('QINIU_BUCKET', '');
        $this->domain = env('QINIU_DOMAIN', '');
        $upManager = new UploadManager();
        $auth = new Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->bucket);
        $key = md5(time());
        $name = $this->formatName($this->column);
        $string = '';
        if ($this->form->model()->id > 0) {
            $disk = Storage::disk('qiniu');
            $file_url = $disk->downloadUrl($this->form->model()->url);
            $size = $disk->size($this->form->model()->url);
            $files = ($this->form->model()->url);
            $string = "initialPreview: ['{$file_url}'],
                initialPreviewAsData: true,
                initialPreviewConfig: [
                    {type: 'video', size: '$size', filetype: 'video/mp4', caption: '{$files}', url: '{$files}', key: 15}
                ],";
        }
        $url = route('qiniu-upload.file_exists');
        $this->script = <<<SRC
        var config = {
          useCdnDomain: true,
          region: qiniu.region.z0
        };
        $.fn.fileinputLocales['zh'] = {
            fileSingle: '文件',
            filePlural: '个文件',
            browseLabel: '选择 &hellip;',
            removeLabel: '移除',
            removeTitle: '清除选中文件',
            cancelLabel: '取消',
            cancelTitle: '取消进行中的上传',
            uploadLabel: '上传',
            uploadTitle: '上传选中文件',
            msgNo: '没有',
            msgNoFilesSelected: '未选择文件',
            msgCancelled: '取消',
            msgPlaceholder: '选择 {files}...',
            msgZoomModalHeading: '详细预览',
            msgFileRequired: '必须选择一个文件上传.',
            msgSizeTooSmall: '文件 "{name}" (<b>{size} KB</b>) 必须大于限定大小 <b>{minSize} KB</b>.',
            msgSizeTooLarge: '文件 "{name}" (<b>{size} KB</b>) 超过了允许大小 <b>{maxSize} KB</b>.',
            msgFilesTooLess: '你必须选择最少 <b>{n}</b> {files} 来上传. ',
            msgFilesTooMany: '选择的上传文件个数 <b>({n})</b> 超出最大文件的限制个数 <b>{m}</b>.',
            msgFileNotFound: '文件 "{name}" 未找到!',
            msgFileSecured: '安全限制，为了防止读取文件 "{name}".',
            msgFileNotReadable: '文件 "{name}" 不可读.',
            msgFilePreviewAborted: '取消 "{name}" 的预览.',
            msgFilePreviewError: '读取 "{name}" 时出现了一个错误.',
            msgInvalidFileName: '文件名 "{name}" 包含非法字符.',
            msgInvalidFileType: '不正确的类型 "{name}". 只支持 "{types}" 类型的文件.',
            msgInvalidFileExtension: '不正确的文件扩展名 "{name}". 只支持 "{extensions}" 的文件扩展名.',
            msgFileTypes: {
                'image': 'image',
                'html': 'HTML',
                'text': 'text',
                'video': 'video',
                'audio': 'audio',
                'flash': 'flash',
                'pdf': 'PDF',
                'object': 'object'
            },
            msgUploadAborted: '该文件上传被中止',
            msgUploadThreshold: '处理中...',
            msgUploadBegin: '正在初始化...',
            msgUploadEnd: '完成',
            msgUploadEmpty: '无效的文件上传.',
            msgUploadError: '上传出错',
            msgValidationError: '验证错误',
            msgLoading: '加载第 {index} 文件 共 {files} &hellip;',
            msgProgress: '加载第 {index} 文件 共 {files} - {name} - {percent}% 完成.',
            msgSelected: '{n} {files} 选中',
            msgFoldersNotAllowed: '只支持拖拽文件! 跳过 {n} 拖拽的文件夹.',
            msgImageWidthSmall: '图像文件的"{name}"的宽度必须是至少{size}像素.',
            msgImageHeightSmall: '图像文件的"{name}"的高度必须至少为{size}像素.',
            msgImageWidthLarge: '图像文件"{name}"的宽度不能超过{size}像素.',
            msgImageHeightLarge: '图像文件"{name}"的高度不能超过{size}像素.',
            msgImageResizeError: '无法获取的图像尺寸调整。',
            msgImageResizeException: '调整图像大小时发生错误。<pre>{errors}</pre>',
            msgAjaxError: '{operation} 发生错误. 请重试!',
            msgAjaxProgressError: '{operation} 失败',
            ajaxOperations: {
                deleteThumb: '删除文件',
                uploadThumb: '上传文件',
                uploadBatch: '批量上传',
                uploadExtra: '表单数据上传'
            },
            dropZoneTitle: '拖拽文件到这里 &hellip;<br>支持多文件同时上传',
            dropZoneClickTitle: '<br>(或点击{files}按钮选择文件)',
            fileActionSettings: {
                removeTitle: '删除文件',
                uploadTitle: '上传文件',
                downloadTitle: '下载文件',
                uploadRetryTitle: '重试',
                zoomTitle: '查看详情',
                dragTitle: '移动 / 重置',
                indicatorNewTitle: '没有上传',
                indicatorSuccessTitle: '上传',
                indicatorErrorTitle: '上传错误',
                indicatorLoadingTitle: '上传 ...'
            },
            previewZoomButtonTitles: {
                prev: '预览上一个文件',
                next: '预览下一个文件',
                toggleheader: '缩放',
                fullscreen: '全屏',
                borderless: '无边界模式',
                close: '关闭当前预览'
            }
        };
        var requestUrl = qiniu.getUploadUrl(config);
        requestUrl.then((url)=>{
            console.log(url)
            $('#{$name}_file').fileinput({
                language: 'zh',
                uploadUrl: url,
                maxFileCount: 5,
                {$string}
                uploadExtraData: function() {
                    var key = '{$key}';
                    var bucket_key = md5(key+Math.random()*100);
                    
                    var out = {'token':'{$token}', 'key': bucket_key}
                    return out;
                }
            }).on('fileuploaded', function(event, data, previewId, index) {
                var form = data.form, files = data.files, extra = data.extra,
                    response = data.response, reader = data.reader;
                    var init =  $('#{$name}_value').val()+"|";
                    $('#{$name}_value').val(init+response.key);
                    console.log(data);
            });;
        })
SRC;
        
        return parent::render();
    }
    
}
