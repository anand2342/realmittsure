<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NotificationAlert extends Model
{
    protected $table = 'notification_alerts';
    protected $fillable = [
        'marketing_banner',
        'message',
        'redirection_url',
        'created_by',
        'role_visibility',
        'is_active',
    ];
    protected $appends = ['marketing_banner_image_url'];

    public function getVisibleRoleNamesAttribute()
    {
        $slugs = explode(',', $this->role_visibility);
        return Role::whereIn('role_slug', $slugs)->pluck('role_name')->toArray();
    }
    public function getMarketingBannerImageUrlAttribute()
    {
        if ($this->marketing_banner && Storage::disk('public')->exists('uploads/marketing_banner/' . $this->marketing_banner)) {
            return asset('storage/uploads/marketing_banner/' . $this->marketing_banner);
        }
        return ''; // Return a default image or placeholder URL if needed
    }
}
