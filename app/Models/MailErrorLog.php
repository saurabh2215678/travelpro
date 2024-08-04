<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailErrorLog extends Model
{
    use HasFactory;
    protected $table = 'email_error_logs';

    protected $fillable = [
        'msg_subject',
        'msg_body',
        'attachments',
        'msg_from',
        'msg_to',
        'module_name',
        'added_by',
        'ip',
        'record_id',
        'status',
        'error_message',
        'type'
    ];
}
