<?php

namespace App\Console\Commands;

use App\Http\Controllers;
use Illuminate\Console\Command;
use App\Models\Accounts;
use App\Models\Company;

class ImportQbAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $companyData = Company::where('deleted', 0)->where('qbo_realmid', '!=' , '')->get();

        $accounts = [];
        foreach ($companyData as $key => $value) {
            //print_r($value->id);exit();
            $accountData = new \App\Http\Controllers\QuickbooksController();
            $reportData = new \App\Http\Controllers\ReportsController();
     
            $company_id = $value->id;
            $user_id = $value->user_id;

            $accounts[$key] = $accountData->importCronAccounts($company_id, $user_id);
           
            for($i=0; $i<=3; $i++){   
                $reportData->getCronQBOReports($i, $company_id);
            }
        }
        
        $this->info('All accounts are fetched successfully!');
        
    }
}
