<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultAudit extends Model
{
    use HasFactory;

    public $timestamps = false; // we only use created_at

    protected $fillable = [
        'result_id',
        'action',
        'performed_by',
        'old_values',
        'new_values',
        'comment',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}