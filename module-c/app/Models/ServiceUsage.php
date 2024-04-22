<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUsage extends Model
{
    use HasFactory;

    protected $table = 'service_usages';
    public $timestamps = false;

    protected $fillable = [
        'duration_in_ms',
        'api_token_id',
        'service_id',
        'usage_started_at',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
