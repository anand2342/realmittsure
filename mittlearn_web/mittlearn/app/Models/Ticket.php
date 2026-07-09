<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ticket_id', 'date_created', 'module', 'issue', 'screenshot_path', 'logged_by_user','assigned_to',
        'priority', 'status', 'remarks_qd', 'further_remarks', 'created_by',
    ];

    protected $casts = ['date_created' => 'date'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class)->with('user')->latest();
    }

    public function watchers()
    {
        return $this->hasMany(TicketWatcher::class)->with('user');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class)->with('user');
    }

    public function timeLogs()
    {
        return $this->hasMany(TicketTimeLog::class)->with('user');
    }

    public function getVisibleCommentsAttribute()
    {
        $user = Auth::user();
        if (!$user) return collect();
        
        $isCreator = $this->created_by === $user->id;
        $isAssignee = $this->assigned_to === $user->id;
        $role = getUserRoles();
        $isDeveloper = in_array($role, ['qd_developer', 'admin']);
        $isWatcher = $this->watchers()->where('user_id', $user->id)->exists();
        
        if ($isDeveloper || $isAssignee) {
            return $this->comments;
        }
        
        return $this->comments()->where('is_internal', false)->get();
    }

    public function getTotalTimeLoggedAttribute()
    {
        return $this->timeLogs()->sum('hours');
    }

    public function getStatusBadgeAttribute()
    {
        return [
            'open' => 'badge-warning',
            'in_progress' => 'badge-info', 
            'resolved' => 'badge-success',
            'closed' => 'badge-secondary'
        ][$this->status] ?? 'badge-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        return [
            'low' => 'badge-success',
            'medium' => 'badge-warning',
            'high' => 'badge-danger',
            'critical' => 'badge-dark'
        ][$this->priority] ?? 'badge-secondary';
    }

    public function canUserView($user)
    {
        return $this->created_by === $user->id || 
               $this->assigned_to === $user->id ||
               in_array(getUserRoles($user->id), ['admin', 'qd_developer','super_admin']) ||
               $this->watchers()->where('user_id', $user->id)->exists();
    }

    public function canUserEdit($user)
    {
        return $this->assigned_to === $user->id || in_array(getUserRoles($user->id), ['admin', 'qd_developer','super_admin']);
    }
}
