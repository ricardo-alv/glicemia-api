<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\TenantTrait;

class GlucoseDay extends Model
{
    use HasFactory, HasUuids, TenantTrait;

    protected $fillable = ['user_id', 'description', 'date', 'basal'];

    public function glucoses()
    {
        return $this->hasMany(Glucose::class,'glucose_days_id');
    }
}
