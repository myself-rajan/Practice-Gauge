<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Accounts;
use App\Models\AccountsData;
use App\Models\Categories;
use App\Models\CategoryMapping;
use App\Models\Company;
use App\Models\UserDetails;
use App\Models\Settings;
use App\Models\SpecialityPercentage;
use Session;
use DB;
use DateTime;
use PDF;
use Response;
use Auth;

class DashboardReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function dashboardReports()
    {
        \View::share('global_page_title', 'Reports');
        $companyId = Session::get('company')['company_id'];
        $company = Company::where('id', '=', $companyId)->first();
        return view('dashboard_reports', ['company' => $company]);
    }      

    public function monthlyCollectionReport(Request $request)
    {
        $company_id = \Session::get('company')['company_id'];

        $dateVal     = $request->date_val;
        $month       = $request->month;
        $CommaYear   = $request->year;
        $yearData    = explode(',',$CommaYear);
        $filterType = $request->filter_type;
        $compareWith = $request->compare_with;
        $checkedPeriod = $request->checked_period;
       
        $fromDate = new DateTime($request->from_date);
        $toDate = new DateTime($request->to_date);
        $fromDate = $fromDate->format('Y-m-d');
        $toDate = $toDate->format('Y-m-d');

        $labelDispDateOne = date("M d, Y", strtotime($fromDate)).' - '.date("M d, Y", strtotime($toDate));

        $fetchData = $this->getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year='');
        
        if($compareWith == 1) {
            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $endYear = date('Y', strtotime($toDate));
                $splitFromDate = explode("-", $fromDate);
                $fromDate = $endYear."-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = $endYear."-".$splitToDate[1].'-'.$splitToDate[2];
            }
            else if($checkedPeriod == 'PP'){
                $datediff = strtotime($fromDate) - strtotime($toDate);
                $days = round($datediff / (60 * 60 * 24)) - 1;
                $fromDate = new DateTime($request->from_date);
                $from = $fromDate->format('Y-m-d');
                $fromDate = date('Y-m-d', strtotime($days.' days', strtotime($from)));
                $toDate =  date('Y-m-d', strtotime('-1 day', strtotime($from)));
            }

            $labelDispDateTwo = date("M d, Y", strtotime($fromDate)).' - '.date("M d, Y", strtotime($toDate));

            $fetchData1 = $this->getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year=1);

            $monthArr    = [ '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];

            foreach ($monthArr as $key => $value) {
                $fetchData3['year'][0] = $labelDispDateOne;
                $fetchData3['year'][1] = $labelDispDateTwo;
                $fetchData3['tatalSum'][0] = array_sum($fetchData['tatalSum']);
                $fetchData3['tatalSum'][1] = array_sum($fetchData1['tatalSum']);
                $fetchData3[$value][0] = array_sum($fetchData[$value]);
                $fetchData3[$value][1] = array_sum($fetchData1[$value]);

                if($checkedPeriod == 'LTY') {
                    $fetchData['year'][3] = $labelDispDateOne;
                    $fetchData['tatalSum'][3] = array_sum($fetchData1['tatalSum']);
                    $fetchData[$value][3] = array_sum($fetchData1[$value]);
                }
            }
            
        }

        if($compareWith == 1) {
            if($checkedPeriod == 'LTY')
                return view('reports/monthly_collection', ['fetchData' => $fetchData]);
            else
                return view('reports/monthly_collection', ['fetchData' => $fetchData3]);
        }
        else {
            return view('reports/monthly_collection', ['fetchData' => $fetchData]);
        }
    }    

    public function getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year) {
        //$colorCode   = ["#9158ff", "#9AB900", "#4BC0C0", '#8B0000', '#33A933', "#FF6384", "#F03337"];

        $monthArr    = [ '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];

        $month = 0;
        $year = 0;
        if($filterType == 2) {
            $month = $request->month;
        }
        else if($filterType == 3) {
           $month = ltrim(date('m'), '0');
        } 
        else if($filterType == 4) {
           $month = ltrim(date('m') - 1, '0');
        }
        else if($filterType == 7) {
            unset($yearData);
            if($CommaYear == date('Y')) {
                $yearData[] = date('Y');
            }
            else {
                $yearData = explode(',',$CommaYear);
            }
        }
        else if($filterType == 8) {
            unset($yearData);
            if($CommaYear == date('Y')) {
                $yearData[] = date('Y') - 1;
            }
            else {
                $yearData = explode(',',$CommaYear);
            }
        }
        else if($filterType == 5 || $filterType == 6) {
            if($filterType == 5) {
                $gn_month = date('n', time());
            } 
            else {
                $gn_month = date('n', time()) - 3;
            }

            $monthD = [];
            if($gn_month == 1 || $gn_month == 2 || $gn_month == 3) {
                $start = 1;
                $monthD = [1,2,3];
            }
            else if($gn_month == 4 || $gn_month == 5 || $gn_month == 6) {
                $start = 4;
                $monthD = [4,5,6];
            }
            else if($gn_month == 7 || $gn_month == 8 || $gn_month == 9) {
                $start = 7;
                $monthD = [7,8,9];
            }
            else if($gn_month == 10 || $gn_month == 11 || $gn_month == 12) {
                $start = 10;
                $monthD = [10,11,12];
            }
        }
        else if($filterType == 9) {
            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));
            unset($yearData);

            if($checkedPeriod == 'LTY') {
                if($isLast2Year == 1) {
                    for ($i=$startYear; $i <= $endYear; $i++) 
                        $yearData[] = $i;
                } 
                else
                    $yearData = [date('Y') - 2, date('Y') - 1];
            } else {
                for ($i=$startYear; $i <= $endYear; $i++) { 
                    $yearData[] = $i;
                }
            }
        }

             
        //$yearData = array_unique($yearData);
        //$accountTypeArr = ['Income', 'Expense', 'Other Income', 'Other Expense'];
        $accountTypeArr = ['Income'];//'Other Income'

        $getAccountingMethod = Settings::where('company_id' , $company_id)->where('deleted', 0)->pluck('accounting_method')->first();
        $accountMethod   = ($getAccountingMethod) ? $getAccountingMethod : '1';

        $fetchAccountData = Accounts::join('accounts_data', 'accounts_data.qbo_id', '=', 'accounts.qbo_id')
        ->where('accounts.company_id', '=', $company_id)
        ->where('accounts_data.company_id', '=', $company_id)
        ->where('accounts.deleted', '=', 0)
        ->whereIn('accounts.account_type', $accountTypeArr)
        ->where('accounts_data.report_type', $accountMethod)
        ->whereIn('accounts_data.year', $yearData)
        ->select(\DB::raw('MONTH(accounts_data.date) as month'), 'accounts_data.year', 'accounts.account_type', \DB::raw('sum(accounts_data.values) as accountVal'))
        ->groupBy('accounts_data.year', \DB::raw('MONTH(accounts_data.date)'))
        ->orderBy('accounts_data.date', 'asc');

        /*->whereBetween('accounts_data.date', ['2018-02-01', '2018-02-10'])*/
        //print_r($monthArr);exit();
        if($filterType == 9) {
            if($compareWith == 0) {
                $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
            }
            else {
                if($checkedPeriod == 'LTY') {
                    if($isLast2Year == 1) {
                        $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
                    }
                } 
                else {
                    $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
                }
                //$fetchAccountData->whereBetween(\DB::raw('MONTH(accounts_data.date)'), [date('m', strtotime($fromDate)), date('m', strtotime($toDate))]);
                //$fetchAccountData->whereBetween(\DB::raw('DAY(accounts_data.date)'), [date('d', strtotime($fromDate)), date('d', strtotime($toDate))]);
            }
        }
        else if($filterType == 5 || $filterType == 6) {
            $fetchAccountData->whereIn(\DB::raw('MONTH(accounts_data.date)') , $monthD);
        }
        else if($month > 0){
            $fetchAccountData->where(\DB::raw('MONTH(accounts_data.date)') , $month);
        }

        $fetchAccountData = $fetchAccountData->get();  

        $accountsArr = [];
        foreach ($fetchAccountData as $account) {
            $accountsArr[$account->year][$account->month] = $account->accountVal;
        }

        foreach ($monthArr as $monthKey => $monthVal) {
            foreach ($yearData as $yearKey => $yearValue) 
            {
                $fetchData[$monthVal][$yearKey] = isset($accountsArr[$yearValue][$monthKey]) ? $accountsArr[$yearValue][$monthKey] : 0;
            }
        } 
       
        foreach ($yearData as $yearKey => $yearValue) 
        {
            $fetchData['year'][$yearKey]    = $yearValue;
            foreach ($monthArr as $monthKey => $monthVal) {
                $totalData[$yearKey]['data'][$monthKey-1] = isset($accountsArr[$yearValue][$monthKey]) ? $accountsArr[$yearValue][$monthKey] : 0;
            }

            $fetchData['tatalSum'][$yearKey] = isset($totalData[$yearKey]['data'][$monthKey-1]) ? array_sum($totalData[$yearKey]['data']) : 0;
        }

        return $fetchData;
    } 

    public function grossNetCollectionReport(Request $request)
    {
        $company_id = \Session::get('company')['company_id'];

        $dateVal     = $request->date_val;
        $month       = $request->month;
        $CommaYear   = $request->year;
        $yearData    = explode(',',$CommaYear);
        $filterType = $request->filter_type;
        $compareWith = $request->compare_with;
        $checkedPeriod = $request->checked_period;

        $fromDate = new DateTime($request->from_date);
        $toDate = new DateTime($request->to_date);
        $fromDate = $fromDate->format('Y-m-d');
        $toDate = $toDate->format('Y-m-d');

        $labelDispDateOne = date("M d, Y", strtotime($fromDate)).' - '.date("M d, Y", strtotime($toDate));

        $netBarChartData = $this->getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year='');

        /*echo "<pre>";print_r($netBarChartData);exit();
        return view('reports/gross_net_collection', ['netBarChartData' => $netBarChartData]);*/

        if($compareWith == 1) {
            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $endYear = date('Y', strtotime($toDate));
                $splitFromDate = explode("-", $fromDate);
                $fromDate = $endYear."-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = $endYear."-".$splitToDate[1].'-'.$splitToDate[2];
            }
            else if($checkedPeriod == 'PP'){
                $datediff = strtotime($fromDate) - strtotime($toDate);
                $days = round($datediff / (60 * 60 * 24)) - 1;

                $fromDate = new DateTime($request->from_date);
                $from = $fromDate->format('Y-m-d');
                $fromDate = date('Y-m-d', strtotime($days.' days', strtotime($from)));
                $toDate =  date('Y-m-d', strtotime('-1 day', strtotime($from)));
            }

            $labelDispDateTwo = date("M d, Y", strtotime($fromDate)).' - '.date("M d, Y", strtotime($toDate));

            $netBarChartData1 = $this->getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year=1);


            $netBarChartData3['year'][0] = $labelDispDateOne;
            $netBarChartData3['year'][1] = $labelDispDateTwo;
            $netBarChartData3['overallNet'][0] = array_sum($netBarChartData['overallNet']);
            $netBarChartData3['overallNet'][1] = array_sum($netBarChartData1['overallNet']);
            $netBarChartData3['overallIncome'][0] = array_sum($netBarChartData['overallIncome']);
            $netBarChartData3['overallIncome'][1] = array_sum($netBarChartData1['overallIncome']);

            /*Check this*/
            if($checkedPeriod == 'LTY') {
                $netBarChartData['year'] = [$labelDispDateOne,2018,2017,2016];//$labelDispDateOne;
                $netBarChartData['overallNet'][2] = $netBarChartData['overallNet'][0];
                $netBarChartData['overallIncome'][2] = $netBarChartData['overallIncome'][0];
                $netBarChartData['overallNet'][0] = array_sum($netBarChartData1['overallNet']);
                $netBarChartData['overallIncome'][0] = array_sum($netBarChartData1['overallIncome']);
                $netBarChartData['overallNet'][1] = $netBarChartData['overallNet'][1];
                $netBarChartData['overallIncome'][1] = $netBarChartData['overallIncome'][1];
               
            }    
        }
        
        if($compareWith == 1) {
            //return view('reports/gross_net_collection', ['netBarChartData' => $netBarChartData3]);
            if($checkedPeriod == 'LTY')
                return view('reports/gross_net_collection', ['netBarChartData' => $netBarChartData]);
            else
                return view('reports/gross_net_collection', ['netBarChartData' => $netBarChartData3]);
        }
        else {
            return view('reports/gross_net_collection', ['netBarChartData' => $netBarChartData]);
        }

    }

    function getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year) {
        //$colorCode   = ["#9AB900", "#4BC0C0", "#9158ff", "#8B0000", "#FF6384", "#F03337", "#9158ff"];

        if($filterType == 7) {
            unset($yearData);
            if($CommaYear == date('Y')) {
                $yearData[] = date('Y');
            }
            else {
                $yearData = explode(',',$CommaYear);
            }

        }
        else if($filterType == 8) {
            unset($yearData);
            if($CommaYear == date('Y')) {
                $yearData[] = date('Y') - 1;
            }
            else {
                $yearData = explode(',',$CommaYear);
            }
        }
        else if($filterType == 9) {

            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));
            unset($yearData);

            /*for ($i=$startYear; $i <= $endYear; $i++) { 
                $yearData[] = $i;
            }*/
            if($checkedPeriod == 'LTY') {
                if($isLast2Year == 1) {
                    for ($i=$startYear; $i <= $endYear; $i++) 
                        $yearData[] = $i;
                } 
                else
                    $yearData = [date('Y') - 2, date('Y') - 1];
            } else {
                for ($i=$startYear; $i <= $endYear; $i++) { 
                    $yearData[] = $i;
                }
            }
        }

        //$yearData = array_unique($yearData);

        $accountTypeArr          = ['Income'];//'Other Income'
        $fetchNetCollection      = $this->getNetCollectionData($accountTypeArr , $yearData, $month, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year);
        $fetchNetOperatingIncome = $this->getNetOperatingIncomeData($yearData, $month, $filterType, $fromDate, $toDate, 1, $accountType = '', $partCategory = 4, $compareWith, $checkedPeriod, $isLast2Year);

        $fetchNetOperatingIncome1 = $this->getNetOperatingIncomeData($yearData, $month, $filterType, $fromDate, $toDate, $isNetOperating='', $accountType = 1, $partCategory = '', $compareWith, $checkedPeriod, $isLast2Year); //$accountType = 1 -> Only Expense


        $netBarChartData         = [];
        $netBarChartData['year'] = $yearData;

        $categoryList = Categories::where('deleted', 0)->orderBy('categories.sort_by', 'asc')->get();
        $childEmpArr = [];
        $sumChildValue = 0;
        foreach ($yearData as $yearKey => $yearVal) {
            $sumChildValue = 0;
            foreach ($categoryList as $category) {
                if($category->id == 15 || $category->id == 16 || $category->id == 17 || $category->id == 18 || $category->id == 19) {
                    $sumChildValue += isset($fetchNetOperatingIncome1[$category->id][$yearVal]) ? $fetchNetOperatingIncome1[$category->id][$yearVal] : 0;
                }
                $childEmpArr[$yearVal] = $sumChildValue;
            } 

        }
 

        foreach ($yearData as $yearKey => $yearValue) { 
            $netBarChartData['overallNet'][]    = (isset($fetchNetCollection[$yearValue])) ? round($fetchNetCollection[$yearValue], 2) : 0;

            if(isset($childEmpArr[$yearValue])){
                $employeeCostExp = $childEmpArr[$yearValue];
            }
            else{
                $employeeCostExp = 0;
            }
           
            $netCollection = (isset($fetchNetCollection[$yearValue])) ? round($fetchNetCollection[$yearValue], 2) : 0;
            $OperatingExpenses = (isset($fetchNetOperatingIncome[$yearValue])) ? round($fetchNetOperatingIncome[$yearValue], 2) : 0;
            $totalOperatingExpenses = $OperatingExpenses + $employeeCostExp;

            $netBarChartData['overallIncome'][] = (round(($netCollection - $totalOperatingExpenses), 2));
        }

        return $netBarChartData;
    }

    public function operationalExpensesReport(Request $request) {
        $company_id  = \Session::get('company')['company_id'];
        $dateVal     = $request->date_val;
        $month       = $request->month;
        $CommaYear   = $request->year; 
        $yearArr     = explode(',', $CommaYear);
        $filterType = $request->filter_type;
        $compareWith = $request->compare_with;
        $checkedPeriod = $request->checked_period;

        $fromDate = new DateTime($request->from_date);
        $toDate = new DateTime($request->to_date);
        $fromDate = $fromDate->format('Y-m-d');
        $toDate = $toDate->format('Y-m-d');

        $labelDispDateOne = date("M d, Y", strtotime($fromDate)).' - '.date("M d, Y", strtotime($toDate));

        if($compareWith == 1) {
            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));
            unset($yearArr);
            /*for ($i=$startYear; $i <= $endYear; $i++) { 
                $yearArr[] = $i;
            }*/

            if($checkedPeriod == 'LTY') {
                $yearArr = [date('Y') - 2, date('Y') - 1];
            } else {
                for ($i=$startYear; $i <= $endYear; $i++) { 
                    $yearArr[] = $i;
                }
            }
        }

        $categoryBarData = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearArr, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year='');
        
        if($compareWith == 1) {
            $categoryList = Categories::where('deleted', 0)->orderBy('categories.sort_by', 'asc')->get();
        
            foreach ($categoryList as $cKey => $category) {
                $categoryBarData3['label'][$cKey] = $category->name;
                $ttVal = 0;$ttPercent = 0;$ttOptimum = 0;$ttOptimumPerc = 0;$totalCatAmt=0;$totalCatPerc=0;$totalOptimumAmt=0;$totalOptimumPerc=0;$netIncomeAmt=0;$netIncomePerc=0;$netIncomeOptimumAmt=0;$netIncomeOptimumPerc=0;$totalEmployeeAmt=0;$totalEmployeePerc=0;$totalEmployeeOptimumAmt=0;$totalEmployeeOptimumPerc =0;
                foreach ($yearArr as $yearKey => $yearVal) {

                    $categoryBarData3['YrSelect'] = end($yearArr);
                    $categoryBarData3['year'][0] = $labelDispDateOne;
                    //$categoryBarData3['year'][1] = $categoryBarData1['year'][0];

                    $ttVal += $categoryBarData['data'][$category->id-1][$yearVal]['totalVal'];
                    $categoryBarData3['data'][$category->id-1][end($yearArr)+1]['totalVal'] = $ttVal;
                    $ttPercent = $categoryBarData['data'][$category->id-1][$yearVal]['percentage'];
                    $categoryBarData3['data'][$category->id-1][end($yearArr)+1]['percentage'] = $ttPercent;

                    $ttOptimum += $categoryBarData['optimum'][$category->id-1][$yearVal]['totalVal'];
                    $categoryBarData3['optimum'][$category->id-1][end($yearArr)]['totalVal'] = $ttOptimum;
                    $ttOptimumPerc = $categoryBarData['optimum'][$category->id-1][$yearVal]['percentage'];
                    $categoryBarData3['optimum'][$category->id-1][end($yearArr)]['percentage'] = $ttOptimumPerc;

                    $totalCatAmt += $categoryBarData['totalSumCat'][$yearVal]['amount'];
                    $categoryBarData3['totalSumCat'][end($yearArr)+1]['amount'] = $totalCatAmt;
                    $totalCatPerc = $categoryBarData['totalSumCat'][$yearVal]['percentage'];
                    $categoryBarData3['totalSumCat'][end($yearArr)+1]['percentage'] = $totalCatPerc;
                    
                    $totalOptimumAmt += $categoryBarData['totalOptimum'][$yearVal]['amount'];
                    $categoryBarData3['totalOptimum'][end($yearArr)]['amount'] = $totalOptimumAmt;
                    $totalOptimumPerc = $categoryBarData['totalOptimum'][$yearVal]['percentage'];
                    $categoryBarData3['totalOptimum'][end($yearArr)]['percentage'] = $totalOptimumPerc;

                    $netIncomeAmt += $categoryBarData['netIncome'][18][$yearVal]['totalVal'];
                     $categoryBarData3['netIncome']['18'][end($yearArr)+1]['totalVal'] = $netIncomeAmt;
                    $netIncomePerc = $categoryBarData['netIncome'][18][$yearVal]['percentage'];
                    $categoryBarData3['netIncome']['18'][end($yearArr)+1]['percentage'] = $netIncomePerc;

                    $netIncomeOptimumAmt  += $categoryBarData['netIncomeOptimum']['18'][$yearVal]['totalVal'];
                    $categoryBarData3['netIncomeOptimum']['18'][end($yearArr)]['totalVal'] = $netIncomeOptimumAmt;
                    $netIncomeOptimumPerc = $categoryBarData['netIncomeOptimum']['18'][$yearVal]['percentage'];
                    $categoryBarData3['netIncomeOptimum']['18'][end($yearArr)]['percentage'] = $netIncomeOptimumPerc;

                    $totalEmployeeAmt += $categoryBarData['totalEmployeeCost'][$yearVal]['amount'];
                    $categoryBarData3['totalEmployeeCost'][end($yearArr)+1]['amount'] = $totalEmployeeAmt;
                    $totalEmployeePerc = $categoryBarData['totalEmployeeCost'][$yearVal]['percentage'];
                    $categoryBarData3['totalEmployeeCost'][end($yearArr)+1]['percentage'] = $totalEmployeePerc;

                    $totalEmployeeOptimumAmt += $categoryBarData['totalEmployeeCostOptimum'][$yearVal]['amount'];
                    $categoryBarData3['totalEmployeeCostOptimum'][end($yearArr)]['amount'] = $totalEmployeeOptimumAmt;
                    $totalEmployeeOptimumPerc = $categoryBarData['totalEmployeeCostOptimum'][$yearVal]['percentage'];
                     $categoryBarData3['totalEmployeeCostOptimum'][end($yearArr)]['percentage'] = $totalEmployeeOptimumPerc;
                }
            }

            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $endYear = date('Y', strtotime($toDate));
                $splitFromDate = explode("-", $fromDate);
                $fromDate = $endYear."-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = $endYear."-".$splitToDate[1].'-'.$splitToDate[2];
            }
            else if($checkedPeriod == 'PP'){
                $datediff = strtotime($fromDate) - strtotime($toDate);
                $days = round($datediff / (60 * 60 * 24)) - 1;

                $fromDate = new DateTime($request->from_date);
                $from = $fromDate->format('Y-m-d');
                $fromDate = date('Y-m-d', strtotime($days.' days', strtotime($from)));
                $toDate =  date('Y-m-d', strtotime('-1 day', strtotime($from)));
            }

            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));
            unset($yearArr);
            for ($i=$startYear; $i <= $endYear; $i++) { 
                $yearArr[] = $i;
            }

            $labelDispDateTwo = date("M d, Y", strtotime($fromDate)).' - '.date("M d, Y", strtotime($toDate));
            $categoryBarData1 = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearArr, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year=1);

            foreach ($categoryList as $cKey => $category) {
                $ttVal = 0;$ttPercent = 0;$ttOptimum = 0;$ttOptimumPerc = 0;$totalCatAmt=0;$totalCatPerc=0;$totalOptimumAmt=0;$totalOptimumPerc=0;$netIncomeAmt=0;$netIncomePerc=0;$netIncomeOptimumAmt=0;$netIncomeOptimumPerc=0;$totalEmployeeAmt=0;$totalEmployeePerc=0;$totalEmployeeOptimumAmt=0;$totalEmployeeOptimumPerc =0;
                $categoryBarData3['label'][$cKey] = $category->name;
                foreach ($yearArr as $yearKey => $yearVal) {
                    /*Last Two Year in Filter*/
                    if($checkedPeriod == 'LTY') {
                        $categoryBarData['year'][2] = $labelDispDateOne;
                        $ttVal += $categoryBarData1['data'][$category->id-1][$yearVal]['totalVal'];
                        $categoryBarData['data'][$category->id-1][current($yearArr)]['totalVal'] = $ttVal;
                        $ttPercent = $categoryBarData1['data'][$category->id-1][$yearVal]['percentage'];
                        $categoryBarData['data'][$category->id-1][current($yearArr)]['percentage'] = $ttPercent;

                        $totalCatAmt += $categoryBarData1['totalSumCat'][$yearVal]['amount'];
                        $categoryBarData['totalSumCat'][current($yearArr)]['amount'] = $totalCatAmt;
                        $totalCatPerc = $categoryBarData1['totalSumCat'][$yearVal]['percentage'];
                        $categoryBarData['totalSumCat'][current($yearArr)]['percentage'] = $totalCatPerc;
                        
                        $netIncomeAmt += $categoryBarData1['netIncome'][18][$yearVal]['totalVal'];
                         $categoryBarData['netIncome']['18'][current($yearArr)]['totalVal'] = $netIncomeAmt;
                        $netIncomePerc = $categoryBarData1['netIncome'][18][$yearVal]['percentage'];
                        $categoryBarData['netIncome']['18'][current($yearArr)]['percentage'] = $netIncomePerc;

                        $totalEmployeeAmt += $categoryBarData1['totalEmployeeCost'][$yearVal]['amount'];
                        $categoryBarData['totalEmployeeCost'][current($yearArr)]['amount'] = $totalEmployeeAmt;
                        $totalEmployeePerc = $categoryBarData1['totalEmployeeCost'][$yearVal]['percentage'];
                        $categoryBarData['totalEmployeeCost'][current($yearArr)]['percentage'] = $totalEmployeePerc;
                    }
                    else {
                        $categoryBarData3['year'][1] = $labelDispDateTwo;
                        $ttVal += $categoryBarData1['data'][$category->id-1][$yearVal]['totalVal'];
                        $categoryBarData3['data'][$category->id-1][current($yearArr)]['totalVal'] = $ttVal;
                        $ttPercent = $categoryBarData1['data'][$category->id-1][$yearVal]['percentage'];
                        $categoryBarData3['data'][$category->id-1][current($yearArr)]['percentage'] = $ttPercent;

                        $totalCatAmt += $categoryBarData1['totalSumCat'][$yearVal]['amount'];
                        $categoryBarData3['totalSumCat'][current($yearArr)]['amount'] = $totalCatAmt;
                        $totalCatPerc = $categoryBarData1['totalSumCat'][$yearVal]['percentage'];
                        $categoryBarData3['totalSumCat'][current($yearArr)]['percentage'] = $totalCatPerc;
                        
                        $netIncomeAmt += $categoryBarData1['netIncome'][18][$yearVal]['totalVal'];
                         $categoryBarData3['netIncome']['18'][current($yearArr)]['totalVal'] = $netIncomeAmt;
                        $netIncomePerc = $categoryBarData1['netIncome'][18][$yearVal]['percentage'];
                        $categoryBarData3['netIncome']['18'][current($yearArr)]['percentage'] = $netIncomePerc;

                        $totalEmployeeAmt += $categoryBarData1['totalEmployeeCost'][$yearVal]['amount'];
                        $categoryBarData3['totalEmployeeCost'][current($yearArr)]['amount'] = $totalEmployeeAmt;
                        $totalEmployeePerc = $categoryBarData1['totalEmployeeCost'][$yearVal]['percentage'];
                        $categoryBarData3['totalEmployeeCost'][current($yearArr)]['percentage'] = $totalEmployeePerc;
                    }
                    
                }
            }
        }
        //echo "<pre>";print_r($categoryBarData);exit();
        if($compareWith == 1) {
            
            if($checkedPeriod == 'LTY')
                return view('reports/operational_expenses', ['categoryBarData' => $categoryBarData]);
            else
                return view('reports/operational_expenses', ['categoryBarData' => $categoryBarData3]);
        }
        else {
            return view('reports/operational_expenses', ['categoryBarData' => $categoryBarData]);
        }
    }

    public function getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearArr, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year) {
       
        /*$colorCode   = [ "#9AB900", "#4BC0C0", "#FF6384", "#36A2EB", "#FF6384", "#F03337", "#9158ff"];*/

        if($filterType == 7) {
            unset($yearArr);
            if($CommaYear == date('Y')) {
                $yearArr[] = date('Y');
            }
            else {
                $yearArr = explode(',',$CommaYear);
            }
        }
        else if($filterType == 8) {
            unset($yearArr);
            if($CommaYear == date('Y')) {
                $yearArr[] = date('Y') - 1;
            }
            else {
                $yearArr = explode(',',$CommaYear);
            }
        }
        else if($filterType == 9) {
            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));

            unset($yearArr);
            
            if($checkedPeriod == 'LTY') {
                if($isLast2Year == 1) {
                    for ($i=$startYear; $i <= $endYear; $i++) { 
                        $yearArr[] = $i;
                    }
                } 
                else
                    $yearArr = [date('Y') - 2, date('Y') - 1];
            } else {
                for ($i=$startYear; $i <= $endYear; $i++) { 
                    $yearArr[] = $i;
                }
            }
        }
    
        //$yearArr = array_unique($yearArr);

        $categoryBarData = []; 

        $categoryBarData['YrSelect'] = isset($yearArr) ? end($yearArr) : 2019;

        $categoryList           = Categories::where('deleted', 0)->orderBy('categories.sort_by', 'asc')->get();
        $accountTypeArr         = ['Income'];//'Other Income'
        $netCollectionData      = $this->getNetCollectionData($accountTypeArr, $yearArr, $month, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year);       
        $nerOperatingIncomeData = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating='', $accountType = '', $partCategory = '', $compareWith, $checkedPeriod, $isLast2Year);   
        
        $fetchNetOperatingIncome1 = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating = 1, $accountType = '', $partCategory = 4, $compareWith, $checkedPeriod, $isLast2Year); //$accountType = 1 -> Only Expense

        $fetchNetOperatingIncome2 = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating='', $accountType = 1, $partCategory = '', $compareWith, $checkedPeriod, $isLast2Year); //$accountType = 1 -> Only Expense

        $fetchNetOperatingIncome = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating = 1, $accountType = '', $partCategory = 1, $compareWith, $checkedPeriod, $isLast2Year);

        $fetchNetOperatingIncome3 = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating = 1, $accountType = '', $partCategory = 2, $compareWith, $checkedPeriod, $isLast2Year);

        $fetchNetOperatingIncome4 = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating = '', $accountType = '', $partCategory = 3, $compareWith, $checkedPeriod, $isLast2Year);

        $fetchNetOperatingIncome5 = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating = 1, $accountType = '', $partCategory = 5, $compareWith, $checkedPeriod, $isLast2Year);// Only for Miscellanous income

        $typesOfPractice = Company::where('id', $company_id)->pluck('types_of_practice')->first();  

        /*if(isset($categoryList)) { // Only 7 Categories to be shown on chart
            unset($categoryList[0]);  
            unset($categoryList[7]);
            unset($categoryList[8]); 
            unset($categoryList[9]);
            unset($categoryList[10]);
            unset($categoryList[11]);
        } */

        /*Created On : 2019-06-11
          Purpose    : Total Employee costs*/
        $childEmpArr = [];
        $sumChildValue = 0;
        foreach ($yearArr as $yearKey => $yearVal) {
            $sumChildValue = 0;
            foreach ($categoryList as $category) {
                if($category->id == 15 || $category->id == 16 || $category->id == 17 || $category->id == 18 || $category->id == 19) {
                    $sumChildValue += isset($fetchNetOperatingIncome2[$category->id][$yearVal]) ? $fetchNetOperatingIncome2[$category->id][$yearVal] : 0;
                }
                $childEmpArr[$yearVal] = $sumChildValue;
            } 

        }
    
        $categoryBarData['year'] = $yearArr; 
        foreach ($categoryList as $cKey => $category) {
            $categoryBarData['label'][$cKey]   = $category->name;

            foreach ($yearArr as $yearKey => $yearVal) {

                //$categoryBarData['color'][$yearVal]  = $colorCode[$yearKey];
                //Apply Persentage Calculation

                if($category->id == 6) { //Employee Cost
                    if(isset($childEmpArr[$yearVal])){
                        $applyPerTotal = $childEmpArr[$yearVal];
                        if(isset($netCollectionData[$yearVal]))
                            $applyPerCal = ((($childEmpArr[$yearVal]) /$netCollectionData[$yearVal])*100);
                        else
                            $applyPerCal = 0;
                    }
                    else{
                        $applyPerTotal = 0;
                        $applyPerCal = 0;
                    }
                } 
                else {
                    if(isset($nerOperatingIncomeData[$category->id][$yearVal])) {
                        $applyPerTotal = $nerOperatingIncomeData[$category->id][$yearVal];
                        $applyPerCal = ((($nerOperatingIncomeData[$category->id][$yearVal]) /$netCollectionData[$yearVal])*100);
                    }
                    else  {
                        $applyPerTotal = 0;
                        $applyPerCal = 0;
                    }
                }


                if($category->id == 14) {
                    $netCollection = (isset($netCollectionData[$yearVal])) ? $netCollectionData[$yearVal] : 0;

                    $employeeCostExp = 0;
                    if(isset($childEmpArr[$yearVal])){
                        $employeeCostExp = $childEmpArr[$yearVal];
                    }

                    $totalOperatingExpenses =  (isset($fetchNetOperatingIncome1[$yearVal])) ? $fetchNetOperatingIncome1[$yearVal] : 0 + $employeeCostExp;
                     //print_r($totalOperatingExpenses);

                    if($netCollection != 0) {
                        $applyPerTotal = ($netCollection - $totalOperatingExpenses) - $employeeCostExp;
                        $applyPerCal = ((($netCollection - $totalOperatingExpenses - $employeeCostExp) / $netCollection)*100);
                    }
                    else {
                        $applyPerTotal = 0;
                        $applyPerCal = 0;
                    }
                }


                $categoryBarData['data'][$cKey][$yearVal]['totalVal'] = $applyPerTotal;
                $categoryBarData['data'][$cKey][$yearVal]['percentage'] = $applyPerCal;



                //$categoryBarData['data']['totalCol'][$yearVal] = 0;
                //$categoryBarData['data']['totalCol'][$yearVal] += $categoryBarData['data'][$cKey][$yearVal]['totalVal'];


                //OPTIMUM CALCULATIONS
                $getSpecalityPercentage = SpecialityPercentage::where('category_id', $category->id)->where('type_id', $typesOfPractice)->pluck('percentage')->first();
                $getValPersentage       = ($getSpecalityPercentage) ? ($getSpecalityPercentage) : '0';               

                //FoR AMOUNT CALCULATION
                $percentageValue       = ($getSpecalityPercentage) ? ($getSpecalityPercentage/100) : '0'; 
                $netCollectionValue    = isset($netCollectionData[$yearVal]) ? $netCollectionData[$yearVal] : '0'; 
                $getValAmount          = $percentageValue*$netCollectionValue;

                $categoryBarData['optimum'][$cKey][$yearVal]['totalVal'] = $getValAmount;
                $categoryBarData['optimum'][$cKey][$yearVal]['percentage'] = $getValPersentage;
            }

        } 

        

        foreach ($yearArr as $yearKey => $yearVal) {

            foreach ($categoryList as $cKey => $category) {

                if($category->id == 6) { //Employee Cost
                    if(isset($childEmpArr[$yearVal])){
                        $applyPerTotal = $childEmpArr[$yearVal];
                        if(isset($netCollectionData[$yearVal]))
                            $applyPerCal = ((($childEmpArr[$yearVal]) /$netCollectionData[$yearVal])*100);
                        else
                            $applyPerCal = 0;
                    }
                    else{
                        $applyPerTotal = 0;
                        $applyPerCal = 0;
                    }
                } 
                else { //Array having child values
                    if($category->id == 15 || $category->id == 16 || $category->id == 17 || $category->id == 18 || $category->id == 19) { 
                        $applyPerTotal = 0;
                        $applyPerCal = 0;
                    } 
                    else {
                        if(isset($fetchNetOperatingIncome2[$category->id][$yearVal])) {
                            $applyPerTotal = $fetchNetOperatingIncome2[$category->id][$yearVal];
                            $applyPerCal = ((($fetchNetOperatingIncome2[$category->id][$yearVal]) /$netCollectionData[$yearVal])*100);
                        }
                        else  {
                            $applyPerTotal = 0;
                            $applyPerCal = 0;
                        }
                    }
                }

                $dataval['data'][$yearVal][$category->id] = $applyPerTotal;
                $percentval['data'][$yearVal][$category->id] = $applyPerCal;

                //OPTIMUM CALCULATIONS
                $getSpecalityPercentage = SpecialityPercentage::where('category_id', $category->id)->where('type_id', $typesOfPractice)->pluck('percentage')->first();
                $getValPersentage       = ($getSpecalityPercentage) ? ($getSpecalityPercentage) : '0';               
                //FoR AMOUNT CALCULATION
                $percentageValue       = ($getSpecalityPercentage) ? ($getSpecalityPercentage/100) : '0'; 
                $netCollectionValue    = isset($netCollectionData[$yearVal]) ? $netCollectionData[$yearVal] : '0'; 
                $getValAmount          = round($percentageValue*$netCollectionValue, 2);

                $dataval['optimum'][$yearVal][$category->id] = $getValAmount;
                $percentval['optimum'][$yearVal][$category->id] = $getValPersentage;

                //echo "<pre>";print_r($percentval['optimum']);echo "* ";

            }

            if(isset($dataval['optimum'][$yearVal])) {
                unset($dataval['optimum'][$yearVal][1]);
                unset($dataval['optimum'][$yearVal][14]); 
                unset($dataval['optimum'][$yearVal][15]); 
                unset($dataval['optimum'][$yearVal][16]); 
                unset($dataval['optimum'][$yearVal][17]); 
                unset($dataval['optimum'][$yearVal][18]); 
                unset($dataval['optimum'][$yearVal][19]);
                unset($dataval['optimum'][$yearVal][7]); 
                unset($dataval['optimum'][$yearVal][8]); 
                unset($dataval['optimum'][$yearVal][9]); 
                unset($dataval['optimum'][$yearVal][10]); 
                unset($dataval['optimum'][$yearVal][11]); 
                unset($dataval['optimum'][$yearVal][12]);  
            } 

            if(isset($percentval['optimum'][$yearVal])) {
                unset($percentval['optimum'][$yearVal][1]);
                unset($percentval['optimum'][$yearVal][14]);
                unset($percentval['optimum'][$yearVal][15]);
                unset($percentval['optimum'][$yearVal][16]);
                unset($percentval['optimum'][$yearVal][17]);
                unset($percentval['optimum'][$yearVal][18]);
                unset($percentval['optimum'][$yearVal][19]);
                unset($percentval['optimum'][$yearVal][7]);
                unset($percentval['optimum'][$yearVal][8]);
                unset($percentval['optimum'][$yearVal][9]);
                unset($percentval['optimum'][$yearVal][10]);
                unset($percentval['optimum'][$yearVal][11]);
                unset($percentval['optimum'][$yearVal][12]);
            }
            
            $categoryBarData['totalSumCat'][$yearVal]['amount'] = isset($dataval['data'][$yearVal]) ? array_sum($dataval['data'][$yearVal]) : 0;
            $categoryBarData['totalSumCat'][$yearVal]['percentage'] = isset($percentval['data'][$yearVal]) ? array_sum($percentval['data'][$yearVal]) : 0;

            $categoryBarData['totalOptimum'][$yearVal]['amount'] = isset($dataval['optimum'][$yearVal]) ? array_sum($dataval['optimum'][$yearVal]) : 0;
            $categoryBarData['totalOptimum'][$yearVal]['percentage'] = isset($percentval['optimum'][$yearVal]) ? array_sum($percentval['optimum'][$yearVal]) : 0;
        }

        foreach ($yearArr as $yearKey => $yearVal) {
            $feeCollected = (isset($fetchNetOperatingIncome[$yearVal])) ? $fetchNetOperatingIncome[$yearVal] : 0;

            $employeeCostExp = 0;
            if(isset($childEmpArr[$yearVal])){
                $employeeCostExp = $childEmpArr[$yearVal];
            }

            $otherIncomeMiscellaousIncome = isset($fetchNetOperatingIncome5[$yearVal]) ? $fetchNetOperatingIncome5[$yearVal] : 0;

            $totalExpOtherExp = $employeeCostExp + (isset($fetchNetOperatingIncome3[$yearVal])) ? $fetchNetOperatingIncome3[$yearVal] : 0;


            if($netCollection != 0) {
                $applyPerTotal = ($otherIncomeMiscellaousIncome + $feeCollected) - $totalExpOtherExp - $employeeCostExp;
                $applyPerCal = (((($otherIncomeMiscellaousIncome + $feeCollected) - $totalExpOtherExp - $employeeCostExp) / $netCollection)*100);
            }
            else {
                $applyPerTotal = 0;
                $applyPerCal = 0;
            }
        
            $categoryBarData['netIncome'][$cKey][$yearVal]['totalVal'] = $applyPerTotal;
            $categoryBarData['netIncome'][$cKey][$yearVal]['percentage'] = $applyPerCal;

            foreach ($categoryList as $cKey => $category) {
                $getSpecalityPercentage = SpecialityPercentage::where('category_id', $category->id)->where('type_id', $typesOfPractice)->pluck('percentage')->first();
                $getValPersentage       = ($getSpecalityPercentage) ? ($getSpecalityPercentage) : '0';               

                $percentageValue       = ($getSpecalityPercentage) ? ($getSpecalityPercentage/100) : '0'; 
                $netCollectionValue    = isset($netCollectionData[$yearVal]) ? $netCollectionData[$yearVal] : '0'; 
                $getValAmount          = $percentageValue*$netCollectionValue;

                $dataval['optimum'][$yearVal][$category->id] = $getValAmount;
                $percentval['optimum'][$yearVal][$category->id] = $getValPersentage;
            }

            if(isset($dataval['optimum'][$yearVal])) { /*To remove the net operating income*/
                unset($dataval['optimum'][$yearVal][1]);
                unset($dataval['optimum'][$yearVal][14]); 
                unset($dataval['optimum'][$yearVal][15]); 
                unset($dataval['optimum'][$yearVal][16]); 
                unset($dataval['optimum'][$yearVal][17]); 
                unset($dataval['optimum'][$yearVal][18]); 
                unset($dataval['optimum'][$yearVal][19]); 
            } 

            if(isset($percentval['optimum'][$yearVal])) {
                unset($percentval['optimum'][$yearVal][1]);
                unset($percentval['optimum'][$yearVal][14]);
                unset($percentval['optimum'][$yearVal][15]); 
                unset($percentval['optimum'][$yearVal][16]); 
                unset($percentval['optimum'][$yearVal][17]); 
                unset($percentval['optimum'][$yearVal][18]); 
                unset($percentval['optimum'][$yearVal][19]);  
            }

            $categoryBarData['netIncomeOptimum'][$cKey][$yearVal]['totalVal'] = isset($dataval['optimum'][$yearVal]) ? array_sum($dataval['optimum'][$yearVal]) : 0;
            $categoryBarData['netIncomeOptimum'][$cKey][$yearVal]['percentage'] = isset($percentval['optimum'][$yearVal]) ? array_sum($percentval['optimum'][$yearVal]) : 0;
        }


        foreach ($yearArr as $yearKey => $yearVal) {

            foreach ($categoryList as $cKey => $category) {

                if(isset($fetchNetOperatingIncome4[$category->id][$yearVal])) {
                    $applyPerTotal = $fetchNetOperatingIncome4[$category->id][$yearVal];
                    $applyPerCal = ((($fetchNetOperatingIncome4[$category->id][$yearVal]) /$netCollectionData[$yearVal])*100);
                }
                else  {
                    $applyPerTotal = 0;
                    $applyPerCal = 0;
                }

                $dataval['data'][$yearVal][$category->id] = $applyPerTotal;
                $percentval['data'][$yearVal][$category->id] = $applyPerCal;

                //OPTIMUM CALCULATIONS
                $getSpecalityPercentage = SpecialityPercentage::where('category_id', $category->id)->where('type_id', $typesOfPractice)->pluck('percentage')->first();
                $getValPersentage       = ($getSpecalityPercentage) ? ($getSpecalityPercentage) : '0';               
                //FoR AMOUNT CALCULATION
                $percentageValue       = ($getSpecalityPercentage) ? ($getSpecalityPercentage/100) : '0'; 
                $netCollectionValue    = isset($netCollectionData[$yearVal]) ? $netCollectionData[$yearVal] : '0'; 
                $getValAmount          = round($percentageValue*$netCollectionValue, 2);

                $dataval['optimum'][$yearVal][$category->id] = $getValAmount;
                $percentval['optimum'][$yearVal][$category->id] = $getValPersentage;

            }

            if(isset($dataval['optimum'][$yearVal])) {
                unset($dataval['optimum'][$yearVal][1]);
                unset($dataval['optimum'][$yearVal][2]);
                unset($dataval['optimum'][$yearVal][3]);
                unset($dataval['optimum'][$yearVal][4]);
                unset($dataval['optimum'][$yearVal][5]);
                unset($dataval['optimum'][$yearVal][6]);
                unset($dataval['optimum'][$yearVal][7]);
                unset($dataval['optimum'][$yearVal][8]);
                unset($dataval['optimum'][$yearVal][9]); 
                unset($dataval['optimum'][$yearVal][10]); 
                unset($dataval['optimum'][$yearVal][11]); 
                unset($dataval['optimum'][$yearVal][12]); 
                unset($dataval['optimum'][$yearVal][13]); 
                unset($dataval['optimum'][$yearVal][14]); 
            } 

            if(isset($percentval['optimum'][$yearVal])) {
                unset($percentval['optimum'][$yearVal][1]);
                unset($percentval['optimum'][$yearVal][2]);
                unset($percentval['optimum'][$yearVal][3]);
                unset($percentval['optimum'][$yearVal][4]);
                unset($percentval['optimum'][$yearVal][5]);
                unset($percentval['optimum'][$yearVal][6]);
                unset($percentval['optimum'][$yearVal][7]);
                unset($percentval['optimum'][$yearVal][8]);
                unset($percentval['optimum'][$yearVal][9]); 
                unset($percentval['optimum'][$yearVal][10]); 
                unset($percentval['optimum'][$yearVal][11]); 
                unset($percentval['optimum'][$yearVal][12]); 
                unset($percentval['optimum'][$yearVal][13]); 
                unset($percentval['optimum'][$yearVal][14]); 
            }

            
            $categoryBarData['totalEmployeeCost'][$yearVal]['amount'] = isset($dataval['data'][$yearVal]) ? array_sum($dataval['data'][$yearVal]) : 0;
            $categoryBarData['totalEmployeeCost'][$yearVal]['percentage'] = isset($percentval['data'][$yearVal]) ? array_sum($percentval['data'][$yearVal]) : 0;

            $categoryBarData['totalEmployeeCostOptimum'][$yearVal]['amount'] = isset($dataval['optimum'][$yearVal]) ? array_sum($dataval['optimum'][$yearVal]) : 0;
            $categoryBarData['totalEmployeeCostOptimum'][$yearVal]['percentage'] = isset($percentval['optimum'][$yearVal]) ? array_sum($percentval['optimum'][$yearVal]) : 0;
        }

        //echo "<pre>";print_r($categoryBarData);exit();
        return $categoryBarData;
        //return json_encode($categoryBarData);
    }

    public function getNetCollectionData($accountTypeArr=[], $yearArr=[], $month, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year)
    {
        $company_id  = \Session::get('company')['company_id'];

        $month = 0;
        $year = 0;
        if($filterType == 2) {
            $month = $request->month;
        }
        else if($filterType == 3) {
           $month = ltrim(date('m'), '0');
        } 
        else if($filterType == 4) {
           $month = ltrim(date('m') - 1, '0');
        }
        else if($filterType == 5 || $filterType == 6) {
            if($filterType == 5) {
                $gn_month = date('n', time());
            } 
            else {
                $gn_month = date('n', time()) - 3;
            }

            $monthD = [];
            if($gn_month == 1 || $gn_month == 2 || $gn_month == 3) {
                $start = 1;
                $monthD = [1,2,3];
            }
            else if($gn_month == 4 || $gn_month == 5 || $gn_month == 6) {
                $start = 4;
                $monthD = [4,5,6];
            }
            else if($gn_month == 7 || $gn_month == 8 || $gn_month == 9) {
                $start = 7;
                $monthD = [7,8,9];
            }
            else if($gn_month == 10 || $gn_month == 11 || $gn_month == 12) {
                $start = 10;
                $monthD = [10,11,12];
            }
        }

        //print_r($yearArr);exit();

        $getAccountingMethod = Settings::where('company_id' , $company_id)->where('deleted', 0)->pluck('accounting_method')->first();
        $accountMethod   = ($getAccountingMethod) ? $getAccountingMethod : '1';

        $fetchAccountData = Accounts::join('accounts_data', 'accounts_data.qbo_id', '=', 'accounts.qbo_id')
        ->where('accounts.company_id', '=', $company_id)
        ->where('accounts_data.company_id', '=', $company_id)
        ->where('accounts.deleted', '=', 0)
        ->whereIn('accounts.account_type', $accountTypeArr)
        ->where('accounts_data.report_type', $accountMethod)
        ->whereIn('accounts_data.year', $yearArr)
        ->select(\DB::raw('MONTH(accounts_data.date) as month'), 'accounts_data.year', 'accounts.account_type', \DB::raw('sum(accounts_data.values) as accountVal'))
        ->groupBy('accounts_data.year')
        ->orderBy('accounts_data.date', 'asc');

        if($filterType == 9) {
            if($compareWith == 0) {
                $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
            }
            else {
                if($checkedPeriod == 'LTY') {
                    if($isLast2Year == 1) {
                        $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
                    }
                } 
                else {
                    $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
                }
                //$fetchAccountData->whereBetween(\DB::raw('MONTH(accounts_data.date)'), [date('m', strtotime($fromDate)), date('m', strtotime($toDate))]);
                //$fetchAccountData->whereBetween(\DB::raw('DAY(accounts_data.date)'), [date('d', strtotime($fromDate)), date('d', strtotime($toDate))]);
            }

           /* if($compareWith == 0) {
                $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
            }
            else {
                $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
                //$fetchAccountData->whereBetween(\DB::raw('MONTH(accounts_data.date)'), [date('m', strtotime($fromDate)), date('m', strtotime($toDate))]);
                //$fetchAccountData->whereBetween(\DB::raw('DAY(accounts_data.date)'), [date('d', strtotime($fromDate)), date('d', strtotime($toDate))]);
            }*/
        }
        else if($filterType == 5 || $filterType == 6) {
            $fetchAccountData->whereIn(\DB::raw('MONTH(accounts_data.date)') , $monthD);
        }
        else if($month > 0){
            $fetchAccountData->where(\DB::raw('MONTH(accounts_data.date)') , $month);
        }

        $fetchAccountData = $fetchAccountData->get();      


        $accountsArr = [];
        if(count($fetchAccountData) > 0)
        {
            foreach ($fetchAccountData as $account) {
                $accountsArr[$account->year] = round($account->accountVal, 2);
            }
        }        


        return $accountsArr;
    }

    public function getNetOperatingIncomeData($yearArr=[], $month, $filterType, $fromDate, $toDate, $isNetOperating='', $accountType = '', $partCategory = '', $compareWith, $checkedPeriod, $isLast2Year)
    {
        $company_id = \Session::get('company')['company_id'];

        $month = 0;
        $year = 0;
        if($filterType == 2) {
            $month = $request->month;
        }
        else if($filterType == 3) {
           $month = ltrim(date('m'), '0');
        } 
        else if($filterType == 4) {
           $month = ltrim(date('m') - 1, '0');
        }
        else if($filterType == 5 || $filterType == 6) {
            if($filterType == 5) {
                $gn_month = date('n', time());
            } 
            else {
                $gn_month = date('n', time()) - 3;
            }

            $monthD = [];
            if($gn_month == 1 || $gn_month == 2 || $gn_month == 3) {
                $start = 1;
                $monthD = [1,2,3];
            }
            else if($gn_month == 4 || $gn_month == 5 || $gn_month == 6) {
                $start = 4;
                $monthD = [4,5,6];
            }
            else if($gn_month == 7 || $gn_month == 8 || $gn_month == 9) {
                $start = 7;
                $monthD = [7,8,9];
            }
            else if($gn_month == 10 || $gn_month == 11 || $gn_month == 12) {
                $start = 10;
                $monthD = [10,11,12];
            }
        }


        $getAccountingMethod = Settings::where('company_id' , $company_id)->where('deleted', 0)->pluck('accounting_method')->first();
        $accountMethod       = ($getAccountingMethod) ? $getAccountingMethod : '1';

        $fetchAccountData = Categories::leftJoin('category_mapping as cm', 'cm.category_id', '=', 'categories.id')
        ->leftJoin('accounts as a', 'a.id', '=', 'cm.accounts_id')
        ->leftJoin('accounts_data as ad', 'ad.qbo_id', '=', 'a.qbo_id')
        ->where(function($query)use($company_id, $accountMethod, $yearArr, $accountType, $partCategory) {
            $query->where([
                ['categories.deleted', '=', '0'],
                ['cm.deleted', '=', '0'],
                ['a.deleted', '=', '0'],
                ['ad.deleted', '=', '0'],
                ['a.company_id', '=', $company_id],
                ['ad.company_id', '=', $company_id],
                ['ad.report_type', '=', $accountMethod],
            ]);
            //['a.account_type', 'Expense'],
            $query->whereIn('ad.year', $yearArr); 
            if($accountType == 1) {
                //$query->whereIn('a.account_type', ['Expense', 'Other Expense']);
                $query->whereIn('a.account_type', ['Expense', 'Cost of Goods Sold']);
                $query->whereNotIn('categories.id' ,[7,8,9,10,11,12]);
            }
            else if($partCategory == 1) {
                $query->where('categories.id' , 1);
            }
            else if($partCategory == 2) {
                $query->whereNotIn('categories.id' ,[1,6,15,16,17,18,19,12]);
                //$query->where('categories.id', '!=' , 1);
            }
            else if($partCategory == 3) { //total other employee cost
                $query->whereIn('categories.id' ,[15,16,17,18,19]);
            }
            else if($partCategory == 4) { 
                $query->whereIn('a.account_type', ['Expense', 'Cost of Goods Sold']);
                $query->whereIn('categories.id' ,[2,3,4,5,13]);
            }
            else if($partCategory == 5) { //Miscellaous income
                $query->whereIn('categories.id' ,[12]);
            }
        })->select('categories.id as category_id', 'ad.year', \DB::raw('sum(ad.values) as accountVal'))        
        ->orderBy('categories.id', 'asc')
        ->orderBy('ad.year', 'asc');

        if($isNetOperating == 1){
            $fetchAccountData->groupBy('ad.year');
        }
        else{
            $fetchAccountData->groupBy('categories.id', 'ad.year');
        }

        if($filterType == 9) {
            /*if($compareWith == 0) {
                $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
            }
            else {
                $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
            }*/


            if($compareWith == 0) {
                $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
            }
            else {
                if($checkedPeriod == 'LTY') {
                    if($isLast2Year == 1) {
                        $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
                    }
                } 
                else {
                    $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
                }
            }
        }
        else if($filterType == 5 || $filterType == 6) {
            $fetchAccountData->whereIn(\DB::raw('MONTH(ad.date)') , $monthD);
        }
        else if($month > 0){
            $fetchAccountData->where(\DB::raw('MONTH(ad.date)') , $month);
        }


        $fetchAccountData = $fetchAccountData->get();                   

        $accountsArr = [];
        if(count($fetchAccountData) > 0)
        {
            foreach ($fetchAccountData as $account) {
                if($isNetOperating == 1)
                    $accountsArr[$account->year] = round($account->accountVal, 2);
                else
                    $accountsArr[$account->category_id][$account->year] = round($account->accountVal, 2);
            }
        }     

        return $accountsArr;
    }


    /*public function generatePDF1(Request $request) {
        //print_r($_GET['date_val']);exit();
        $company_id = \Session::get('company')['company_id'];

        $dateVal     = 31;//$request->date_val;
        $month       = 0;//$request->month;
        $CommaYear   = "2016,2017,2018,2019";//$request->year;
        $yearData    = explode(',',$CommaYear);
        $filterType = 1;//$request->filter_type;
        $compareWith = $request->compare_with;
       
        $fromDate = new DateTime($request->from_date='');
        $toDate = new DateTime($request->to_date='');
        $fromDate = $fromDate->format('Y-m-d');
        $toDate = $toDate->format('Y-m-d');

        $fetchData = $this->getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $compareWith);

        $netBarChartData = $this->getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $compareWith, $isLast2Year='');

        $categoryBarData = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $isLast2Year='');

        $data = array(
            'fetchData' => $fetchData,
            'netBarChartData' => $netBarChartData, 
            'categoryBarData' => $categoryBarData,
        );
        
        $pdf = PDF::loadView('dashboardReportPdf', $data);
        
        return $pdf->download('dashboardreport.pdf');
    }

    public function generatePDF(Request $request)
    {
        //$data = $request->all();
        $company_id = \Session::get('company')['company_id'];

        $dateVal     = $request->date_val;
        $month       = $request->month;
        $CommaYear   = $request->year;
        $yearData    = explode(',',$CommaYear);
        $filterType = $request->filter_type;
        $compareWith = $request->compare_with;
       
        $fromDate1 = new DateTime($request->from_date);
        $toDate1 = new DateTime($request->to_date);
        $fromDate = $fromDate1->format('Y-m-d');
        $toDate = $toDate1->format('Y-m-d');

        $fromDateIn = $fromDate1->format('d-m-Y');
        $toDateIn = $toDate1->format('d-m-Y');

        $fetchData = $this->getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $isLast2Year='');

        $netBarChartData = $this->getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $isLast2Year='');

        $categoryBarData = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $isLast2Year='');

        $data = array(
            'fetchData' => $fetchData,
            'netBarChartData' => $netBarChartData, 
            'categoryBarData' => $categoryBarData,
            'month' => $month,
            'CommaYear' => $CommaYear,
            'filterType' => $filterType,
            'fromDate' => $fromDateIn,
            'toDate' => $toDateIn
        );


        $fileName          = 'report_dashboard'.time().'.pdf';
        $localDesturl      = storage_path('app/public/').$fileName;
        $pdf = PDF::loadView('dashboardReportPdf', $data)->setPaper('a4', 'landscape')->save($localDesturl);
        $result['redirect'] =  route('report_download_pdf', $fileName);

        return $result;
    }*/

    public function reportDownloadPdf($fileName)
    { 
        $headers        = array('Content-Type: application/pdf');
        $localDesturl  = storage_path('app/public/').$fileName;

        $companyName   = (\Session::get('company')['global_company_name']) ? \Session::get('company')['global_company_name'] : 'ReportDashboard';

        $dwnFilename   = $companyName.'.pdf';

        return Response::download($localDesturl, $dwnFilename, $headers);
    }

    public function verifyReport(Request $request) 
    {
        $companyId = Session::get('company')['company_id'];
        Company::where('id',  $companyId)->update(['is_report_verified' => 1]);
        return $companyId;
    }

    public function practicesStatus() {
        \View::share('global_page_title', 'Practices Status');
        $data['user_id'] = Auth::User()->id;
        $data['role_id'] = Auth::User()->role_id;
        return view('verify_report_list', $data);
    }

    public function updateSubscription(Request $request) {
        //($request->all());exit();
        $user_id = $request->user_id;
        $checked = $request->checked;
        if($checked == 1) {
            $subscribe = 1;
        }
        else {
            $subscribe = 0;
        }
        User::where('id',  $user_id)->update(['is_subscription' => $subscribe]);
        User::where('parent_id',  $user_id)->update(['is_subscription' => $subscribe]);
    }

}
