<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ActionMaster extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = ['name'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
}
