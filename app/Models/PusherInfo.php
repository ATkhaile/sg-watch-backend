<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PusherInfo extends Model
{
    use SoftDeletes;

    protected $table = 'pusher_infos';

    protected $fillable = [
        'push_type',
        'firebase_project_id',
        'firebase_credential_name',
        'firebase_credentials_path',
        'pusher_app_id',
        'pusher_app_key',
        'pusher_app_secret',
        'pusher_app_cluster',
    ];
}
