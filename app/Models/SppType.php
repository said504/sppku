<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'description',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
