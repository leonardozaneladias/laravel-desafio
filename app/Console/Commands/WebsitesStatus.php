<?php

namespace App\Console\Commands;

use App\Models\Websites;
use App\Tenant\TenantScope;
use Illuminate\Console\Command;
use App\Services\WebsiteStatusService;

class WebsitesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica os status de todos os websites cadastrados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $webs = Websites::withoutGlobalScope(TenantScope::class)->get();
        foreach ($webs as $w){
            $response = WebsiteStatusService::getStatus($w->url);
            $w->status()->create([
                'http_code' => $response['httpCode'], 
                'body' => mb_convert_encoding($response['body'], 'UTF-8', 'ISO-8859-1'), 
            ]);
        }
        return 0;
    }
}
