<?php

namespace App\Http\Controllers\Admin;

use App\Models\Websites;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.dashboard');
    }
    
    public function reload()
    {
        $res = [];
        $websites = Websites::with('lastedStatus')->get();
        $res['qtd'] = $websites->count();
        $res['qtd_200'] = 0;

        foreach ($websites as $w){

            if(@$w->lastedStatus->http_code == 200) $res['qtd_200']++;
        }

        return $res;
        

    }
}
