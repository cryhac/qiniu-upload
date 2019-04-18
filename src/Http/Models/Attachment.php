<?php
namespace Encore\QiniuUpload\Http\Models;
use Illuminate\Database\Eloquent\Model;
class Attachment extends Model
{
    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('admin.database.connection') ?: config('database.default'));
        $this->setTable(config('admin.extensions.attachments.table', 'attachments'));
    }
}