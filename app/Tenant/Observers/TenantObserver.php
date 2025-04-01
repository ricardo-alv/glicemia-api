<?php

namespace App\Tenant\Observers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TenantObserver
{
    public function creating(Model $model): void
    {
        if (auth()->check())
            $model->user_id =  auth()->user()->id;
    }
}
