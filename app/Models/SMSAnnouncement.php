<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SMSAnnouncement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sender_id',
        'message',
        'recipient_type',
        'recipient_filter',
        'total_recipients',
        'sent_count',
        'failed_count',
        'status',
        'sent_at',
        'details'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'details' => 'array'
    ];
    
    /**
     * Get the sender (admin user)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    
    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_recipients == 0) {
            return 0;
        }
        
        return round(($this->sent_count / $this->total_recipients) * 100, 2);
    }
}
