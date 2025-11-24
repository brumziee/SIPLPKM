<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filename',
        'imported_rows',
        'errors',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'errors' => 'array',
        'uploaded_at' => 'datetime',
    ];

    // RELASI KE USER
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }
}
