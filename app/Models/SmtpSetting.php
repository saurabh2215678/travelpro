<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SmtpSetting extends Model{

    protected $table = 'smtp_settings';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];  

}