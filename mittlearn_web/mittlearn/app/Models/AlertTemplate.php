<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AlertTemplate extends Model
{
    protected $table = 'alert_templates';
    protected $fillable = ['type','name', 'subject','cc','bcc', 'body', 'action'];
}
