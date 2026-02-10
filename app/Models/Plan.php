<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'speed',
        'validity_period',
        'price',
        'description'
    ];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
}