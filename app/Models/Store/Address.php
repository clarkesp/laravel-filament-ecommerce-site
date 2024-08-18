<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use App\Models\Store;


class Address extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'first_name', 'last_name', 'phone', 'whatsapp', 'street_address', 'city', 'state', 'zip_code'];
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
    public function getFullNameAttribute() {
        return "{$this['first_name']} {$this['last_name']}";
    }
}
