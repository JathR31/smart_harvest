<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'parent_id',
        'conversation_id',
        'subject',
        'content',
        'is_read',
        'read_at',
        'is_replied',
        'priority',
        'sent_as_sms',
        'sms_status',
        'sms_error',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
        'sent_as_sms' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate conversation_id for new threads
        static::creating(function ($message) {
            if (!$message->parent_id && !$message->conversation_id) {
                $message->conversation_id = Str::uuid();
            }
        });
    }

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the parent message (if this is a reply)
     */
    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    /**
     * Get all replies to this message
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get all messages in this conversation
     */
    public function conversation()
    {
        return $this->where('conversation_id', $this->conversation_id)
                    ->orderBy('created_at', 'asc')
                    ->get();
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark message as replied
     */
    public function markAsReplied()
    {
        $this->update([
            'is_replied' => true,
        ]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for inbox (received messages)
     */
    public function scopeInbox($query, $userId)
    {
        return $query->where('receiver_id', $userId)->orderBy('created_at', 'desc');
    }

    /**
     * Scope for sent messages
     */
    public function scopeSent($query, $userId)
    {
        return $query->where('sender_id', $userId)->orderBy('created_at', 'desc');
    }

    /**
     * Scope for conversation threads (root messages only)
     */
    public function scopeThreads($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get reply count for this message
     */
    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }

    /**
     * Get the latest reply
     */
    public function getLatestReplyAttribute()
    {
        return $this->replies()->latest()->first();
    }
}
