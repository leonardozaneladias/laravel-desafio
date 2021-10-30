<?php

namespace App\Http\Controllers\Admin;

use App\Models\Websites;
use App\Http\Controllers\Controller;

class WebsiteStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Websites $website)
    {
        return $website->status;
    }

}
