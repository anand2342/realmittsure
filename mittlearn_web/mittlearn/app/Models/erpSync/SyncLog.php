<?php

namespace App\Models\erpSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SyncLog extends Model
{
    protected $connection = 'erp_sync_lms'; // Sync Database
    protected $table = 'sync_logs';
    protected $fillable = ['table', 'table_id', 'data','status', 'synced_at'];
}

