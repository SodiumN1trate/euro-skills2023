<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration',
        'service_id',
        'token_id',
        'started_at',
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
