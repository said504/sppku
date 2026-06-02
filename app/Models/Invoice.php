<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'spp_type_id',
        'month',
        'year',
        'amount',
        'discount',
        'total_amount',
        'status',
        'due_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function sppType()
    {
        return $this->belongsTo(SppType::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
