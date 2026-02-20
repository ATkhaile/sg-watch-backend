<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddressJp extends Model
{
    protected $table = 'user_address_jp';

    protected $primaryKey = 'address_id';

    public $incrementing = false;

    protected $fillable = [
        'address_id',
        'prefecture',
        'city',
        'ward_town',
        'banchi',
        'building_name',
        'room_no',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }
}
