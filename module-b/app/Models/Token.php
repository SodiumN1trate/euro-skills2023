<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'revocation_date',
        'workspace_id',
    ];


    public function workspace() {
        return $this->belongsTo(Workspace::class);
    }

    public function usages() {
        return $this->hasMany(Usage::class);
    }
}
