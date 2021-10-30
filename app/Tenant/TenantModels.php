<?php

namespace App\Tenant;

use Illuminate\Database\Eloquent\Model;

trait TenantModels {


    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope(new TenantScope());

        static::creating(function(Model $model) {

            $tenant = \Tenant::getTenant();
            $model->user_id = $tenant->id;
            
        });

    }

}