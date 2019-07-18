<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Accounts;
use App\Models\CategoryMapping;
use Session;
use DB;

class AccountsListController extends Controller
{
    public function availableAccounts()
    {
        \View::share('global_page_title', 'Accounts');
        \View::share('global_menu', 48);
        $company_id = Session::get('company')['company_id'];
        //print_r($company_id);exit();
    	$accountsList_n = Accounts::where('company_id', '=', $company_id)->where(function($q) {
             $q->where('account_type', 'Income')->orwhere('account_type', '=', 'Expense')
        ->orwhere('account_type', '=', 'Other Income')->orwhere('account_type', '=', 'Other Expense')->orwhere('account_type', '=', 'Cost of Goods Sold');
    })->where('account_num', '!=','')->orderBy('account_num', 'asc')->get()->toArray();

        $accountsList_empty = Accounts::where('company_id', '=', $company_id)->where(function($q) {
             $q->where('account_type', 'Income')->orwhere('account_type', '=', 'Expense')
        ->orwhere('account_type', '=', 'Other Income')->orwhere('account_type', '=', 'Other Expense')->orwhere('account_type', '=', 'Cost of Goods Sold');
    })->where('account_num', '=','')->orderBy('account_num', 'asc')->get()->toArray();

        $accountsList = array_merge($accountsList_n,$accountsList_empty);
        //echo "<pre>";print_r($accountsList);die;

        return view('accounts.account',['accountsList' => $accountsList]);
    }

    public function accountMapping()
    {
        \View::share('global_page_title', 'Account Mapping');
        \View::share('global_menu', 48);
        $company_id = Session::get('company')['company_id'];
    	$categories = Categories::where('deleted', 0)->get();
        $accountsList_n = \DB::select(\DB::raw("SELECT accounts.id,accounts.account_name,accounts.account_type,accounts.account_num,accounts_data.values FROM accounts LEFT JOIN accounts_data ON accounts_data.qbo_id=accounts.qbo_id WHERE accounts.company_id = $company_id AND accounts.account_num != '' AND (accounts.account_type = 'Income' OR accounts.account_type = 'Expense' OR accounts.account_type = 'Other Income' OR accounts.account_type = 'Other Expense'  OR accounts.account_type = 'Cost of Goods Sold') AND NOT EXISTS ( SELECT * FROM category_mapping WHERE accounts.id = category_mapping.accounts_id ) GROUP BY accounts.qbo_id ORDER BY accounts.account_num ASC"));

        /*SELECT * FROM accounts WHERE company_id = $company_id AND account_num != '' AND (account_type = 'Income' OR account_type = 'Expense' OR account_type = 'Other Income' OR account_type = 'Other Expense' ) AND NOT EXISTS ( SELECT * FROM category_mapping WHERE accounts.id = category_mapping.accounts_id  ) ORDER BY account_num ASC*/

        $accountsList_empty = \DB::select(\DB::raw("SELECT accounts.id,accounts.account_name,accounts.account_type,accounts.account_num,accounts_data.values FROM accounts LEFT JOIN accounts_data ON accounts_data.qbo_id=accounts.qbo_id WHERE accounts.company_id = $company_id AND accounts.account_num = '' AND (accounts.account_type = 'Income' OR accounts.account_type = 'Expense' OR accounts.account_type = 'Other Income' OR accounts.account_type = 'Other Expense' OR accounts.account_type = 'Cost of Goods Sold') AND NOT EXISTS ( SELECT * FROM category_mapping WHERE accounts.id = category_mapping.accounts_id ) GROUP BY accounts.qbo_id ORDER BY accounts.account_num ASC"));

         $accountsList = array_merge($accountsList_n,$accountsList_empty);

        
        $mappingList = Accounts::leftjoin('accounts_data','accounts_data.qbo_id', '=', 'accounts.qbo_id')->join('category_mapping', 'category_mapping.accounts_id', '=', 'accounts.id')->join('categories', 'categories.id', '=', 'category_mapping.category_id')
                         ->select('accounts.*','category_mapping.*','accounts_data.values', 'accounts.id AS account_id')->where('accounts.company_id', '=', $company_id)
                         ->groupBy('accounts.qbo_id')->get();       

        /*$mappingList = Accounts::join('category_mapping', 'category_mapping.accounts_id', '=', 'accounts.id')->join('categories', 'categories.id', '=', 'category_mapping.category_id')
                         ->select('accounts.*','category_mapping.*')->where('accounts.company_id', '=', $company_id)->get();*/

        return view('accounts.account_mapping', ['categories' => $categories, 'accountsList' => $accountsList, 'mappingList' => $mappingList]);
    }

    public function saveMapping(Request $request)
    {
    	$categoryIdArr = $request->categoryIdArr;
        
   		$company_id = Session::get('company')['company_id'];
        CategoryMapping::where('company_id', $company_id)->delete();

        if($categoryIdArr)
        {
            foreach ($categoryIdArr as $keyCategory => $arrData) 
            {	
                if($arrData) 
                {
                	foreach ($arrData as $key => $value) 
                	{
                		$ac_mapping = new CategoryMapping;
    	            	$ac_mapping->accounts_id = $value;
    	            	$ac_mapping->category_id  = $keyCategory;
                        $ac_mapping->company_id  = $company_id;
    	                $ac_mapping->save();

                        /*$accountMapping = CategoryMapping::firstOrNew(array( 'category_id' => $keyCategory));
                        $accountMapping->company_id = $company_id;
                        $accountMapping->accounts_id = $value;
                        $accountMapping->deleted_at = null;
                        $accountMapping->save();*/ 
                	}
                }
            }
        }

        return 1;
    }

    public function searchAccounts(Request $request)
    {
        $search = $request->search;
        $company_id = Session::get('company')['company_id'];

        $accountsList_n = \DB::select(\DB::raw("select * from `accounts` where (`account_type` = 'Income' or `account_type` = 'Expense' or `account_type` = 'Other Income' or `account_type` = 'Other Expense or accounts.account_type = 'Cost of Goods Sold' and (`account_name` like '%$search%' or `account_num` like '%$search%')  and account_num != '' and `company_id` =".$company_id." order by account_num"));

         $accountsList_empty = \DB::select(\DB::raw("select * from `accounts` where (`account_type` = 'Income' or `account_type` = 'Expense' or `account_type` = 'Other Income' or `account_type` = 'Other Expense' or accounts.account_type = 'Cost of Goods Sold') and (`account_name` like '%$search%' or `account_num` like '%$search%')  and account_num = '' and `company_id` =".$company_id." order by account_num"));

         $accountsList = array_merge($accountsList_n,$accountsList_empty);
    
        return view('accounts.search',['accountsList' => $accountsList]);
        
    }

}
