<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageQueue extends Model
{
    protected $table = 'message_queues';

    protected $fillable = [
        'user_id',
        'sender_type',
        'content',
        'media_id',
        'scenario_step_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(MediaUpload::class, 'media_id');
    }

    public function scenarioStep()
    {
        return $this->belongsTo(ScenarioStep::class, 'scenario_step_id');
    }
}
