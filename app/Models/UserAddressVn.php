<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddressVn extends Model
{
    protected $table = 'user_address_vn';

    protected $primaryKey = 'address_id';

    public $incrementing = false;

    protected $fillable = [
        'address_id',
        'province_city',
        'district',
        'ward_commune',
        'detail_address',
        'building_name',
        'room_no',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }
}
