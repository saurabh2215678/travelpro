<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookResponse extends Model{

    protected $table = 'webhook_responses';

    protected $guarded = ['id'];

}