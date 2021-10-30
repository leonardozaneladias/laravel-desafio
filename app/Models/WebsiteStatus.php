<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteStatus extends Model
{
    protected $fillable = [
        'website_id',
        'http_code',
        'body'
    ];

    public function website(){
        return $this->belongsTo(Websites::class);
    }


}
