<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $table = 'workspaces';

    protected $fillable = [
        'title',
        'description',
        'billing_quota_id'
    ];

    public function quota()
    {
        return $this->belongsTo(Quota::class, 'billing_quota_id');
    }
}
