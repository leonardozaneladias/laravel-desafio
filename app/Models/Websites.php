<?php

namespace App\Models;

use App\Tenant\TenantModels;
use Illuminate\Database\Eloquent\Model;

class Websites extends Model
{
    use TenantModels;

    protected $fillable = [
        'url',
        'user_id',
    ];

    public function status(){
        return $this->hasMany(WebsiteStatus::class, 'website_id', 'id')->orderBy('created_at', 'desc')->limit(60);
    }
    
    public function lastedStatus(){
        return $this->hasOne(WebsiteStatus::class, 'website_id', 'id')->latest();
    }

}
