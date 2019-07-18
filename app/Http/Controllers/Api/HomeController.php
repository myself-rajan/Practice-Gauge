<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('home');
    }    

    public function getMonthlyCollection(Request $request) {
        
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

        $colorCode   = ["#9158ff", "#9AB900", "#4BC0C0", '#8B0000', '#33A933', "#FF6384", "#F03337"];
        $monthArr    = [ '1' => 'jan', '2' => 'feb', '3' => 'mar', '4' => 'apr', '5' => 'may', '6' => 'jun', '7' => 'jul', '8' => 'aug', '9' => 'sep', '10' => 'oct', '11' => 'nov', '12' => 'dec'];

        if($compareWith == 1) {
            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));
            unset($yearData);

            for ($i=$startYear; $i <= $endYear; $i++) { 
                $yearData[] = $i;
            }
        }

        $fetchData = $this->getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $colorCode, $isLast2Year='');   
        

        if($compareWith == 1) {
            foreach ($monthArr as $key => $value) {
                $sumMonth = 0;
                $fetchData3[0]['color'] = $fetchData[0]['color'];
                $fetchData3[0]['year'] = $fetchData[0]['year'];

                foreach ($yearData as $yKey => $yValue) {
                   $sumMonth += $fetchData[$yKey]['data'][$key-1];
                   $fetchData3[0]['data'][$key-1] =  $sumMonth;
               }
            }

            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $splitFromDate = explode("-", $fromDate);
                $fromDate = "2019-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = "2019-".$splitToDate[1].'-'.$splitToDate[2];
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

            $colorCode   = ["#9AB900", "#9158ff", "#4BC0C0", '#8B0000', '#33A933', "#FF6384", "#F03337"];

            $fetchData1 = $this->getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $colorCode, $isLast2Year=1);
            
            foreach ($monthArr as $key => $value) {
                $sumMonth = 0;
                $fetchData3[1]['color'] = $fetchData1[0]['color'];
                $fetchData3[1]['year'] = $fetchData1[0]['year'];

                /*Last Two Years*/
                if($checkedPeriod == 'LTY') {
                    $fetchData[2]['color'] = "#FF6384";
                    $fetchData[2]['year'] = $fetchData1[0]['year'];
                }

                foreach ($yearData as $yKey => $yValue) {
                   $sumMonth += isset($fetchData1[$yKey]['data'][$key-1]) ? $fetchData1[$yKey]['data'][$key-1] :  0;
                   $fetchData3[1]['data'][$key-1] =  $sumMonth;

                   /*Last Two Years*/
                    if($checkedPeriod == 'LTY') {
                        $fetchData[2]['data'][$key-1] =  $sumMonth;
                    }
               }
            }
        }
       
        if($compareWith == 1) {
            if($checkedPeriod == 'LTY')
                $data = $fetchData;
            else
                $data = $fetchData3;
        }
        else {
            $data = $fetchData;
        }

        return response()->json(['msg'=>'Monthly data fetched successfully', 'data'=>$data, 'status' => Response::HTTP_OK]);
    }

    public function getMonthlyCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $colorCode, $isLast2Year) {
        
        $monthArr    = [ '1' => 'jan', '2' => 'feb', '3' => 'mar', '4' => 'apr', '5' => 'may', '6' => 'jun', '7' => 'jul', '8' => 'aug', '9' => 'sep', '10' => 'oct', '11' => 'nov', '12' => 'dec'];

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
            if($checkedPeriod == 'LTY') {
                if($isLast2Year == 1) {
                    $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
                }
            } 
            else {
                $fetchAccountData->whereBetween('accounts_data.date', [$fromDate, $toDate]);
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

        foreach ($yearData as $yearKey => $yearValue) 
        {
            $fetchData[$yearKey]['color']   = $colorCode[$yearKey];
            $fetchData[$yearKey]['year']    = $yearValue;

            foreach ($monthArr as $monthKey => $monthVal) {
                $fetchData[$yearKey]['data'][$monthKey-1] = isset($accountsArr[$yearValue][$monthKey]) ? round($accountsArr[$yearValue][$monthKey], 2) : 0;
            }
        }

        return $fetchData;

    }     

    public function getNetCollection(Request $request) {
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

        $netBarChartData = $this->getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year='');

        //print_r($netBarChartData);exit();
        if($compareWith == 1) {
            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $splitFromDate = explode("-", $fromDate);
                $fromDate = "2019-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = "2019-".$splitToDate[1].'-'.$splitToDate[2];
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

            $netBarChartData3[0]['year'][0] = $netBarChartData[0]['year'][0]; 
            $netBarChartData3[0]['year'][1] = $netBarChartData1[0]['year'][0];
            $netBarChartData3[0]['overallNet'][0] = array_sum($netBarChartData[0]['overallNet']); 
            $netBarChartData3[0]['overallNet'][1] = array_sum($netBarChartData1[0]['overallNet']);
            $netBarChartData3[0]['overallIncome'][0] = array_sum($netBarChartData[0]['overallIncome']); 
            $netBarChartData3[0]['overallIncome'][1] = array_sum($netBarChartData1[0]['overallIncome']);

            if($checkedPeriod == 'LTY') {
                $netBarChartData[0]['year'][2] = $netBarChartData1[0]['year'][0];
                $netBarChartData[0]['overallNet'][2] = array_sum($netBarChartData1[0]['overallNet']);
                $netBarChartData[0]['overallIncome'][2] = array_sum($netBarChartData1[0]['overallIncome']);
            }
            //print_r($netBarChartData3);exit();
        }
        
        if($compareWith == 1) {
            if($checkedPeriod == 'LTY')
                $data =$netBarChartData;
            else
                $data = $netBarChartData3;
        }
        else {
            $data = $netBarChartData;
        }

        return response()->json(['msg'=>'Net data fetched successfully', 'data'=>$data, 'status' => Response::HTTP_OK]);
     }

    public function getGrossNetCollectionData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year) {
        $colorCode   = ["#9AB900", "#4BC0C0", "#9158ff", "#8B0000", "#FF6384", "#F03337", "#9158ff"];

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

            if($checkedPeriod == 'LTY') {
                if($isLast2Year == 1) {
                    for ($i=$startYear; $i <= $endYear; $i++)
                        $yearData[] = $i;
                } 
                else
                    $yearData = [date('Y') - 2, date('Y') - 1];
            } else {
                for ($i=$startYear; $i <= $endYear; $i++)
                    $yearData[] = $i;
            }
        }
        
        $accountTypeArr          = ['Income'];//'Other Income'
        $fetchNetCollection      = $this->getNetCollectionData($accountTypeArr , $yearData, $month, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year);
        $fetchNetOperatingIncome = $this->getNetOperatingIncomeData($yearData, $month, $filterType, $fromDate, $toDate, 1, $compareWith, $partCategory = '', $checkedPeriod, $isLast2Year);

        $netBarChartData         = [];
        $netBarChartData[0]['year'] = $yearData;


        foreach ($yearData as $yearKey => $yearValue) 
        {
            $netBarChartData[0]['color']           = $colorCode[$yearKey];
            $netBarChartData[0]['overallNet'][]    = (isset($fetchNetCollection[$yearValue])) ? round($fetchNetCollection[$yearValue], 2) : 0;

            $netCollection = (isset($fetchNetCollection[$yearValue])) ? round($fetchNetCollection[$yearValue], 2) : 0;
            $totalOperatingExpenses = (isset($fetchNetOperatingIncome[$yearValue])) ? round($fetchNetOperatingIncome[$yearValue], 2) : 0;

            //echo "<pre><br>net-";print_r($netCollection);
            //echo "<pre><br>op-";print_r($totalOperatingExpenses);

            $netBarChartData[0]['overallIncome'][] = round(($netCollection - $totalOperatingExpenses), 2);
        }  

        return $netBarChartData;   
    }

    public function getOperationalExpenses(Request $request) {
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
        
        $categoryBarData = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearArr, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isEmp="", $isLast2Year="");
        
        if($compareWith == 1) {
            $categoryBarData3['year'][0] = end($yearArr); 
            $categoryBarData3['color'][end($yearArr)] = "#9AB900"; 
            $categoryBarData3['data'][end($yearArr)] = $categoryBarData['data'][end($yearArr)]; 
            $categoryBarData3['optimum'][end($yearArr)] = $categoryBarData['optimum'][end($yearArr)]; 

            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $splitFromDate = explode("-", $fromDate);
                $fromDate = "2019-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = "2019-".$splitToDate[1].'-'.$splitToDate[2];
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

            $categoryBarData1 = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearArr, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isEmp="", $isLast2Year=1);

            $categoryBarData3['year'][1] = end($yearArr);
            $categoryBarData3['color'][end($yearArr)] = "#4BC0C0";
            $categoryBarData3['data'][end($yearArr)] = $categoryBarData1['data'][end($yearArr)];
            $categoryBarData3['optimum'][end($yearArr)] = $categoryBarData1['optimum'][end($yearArr)];

            if($checkedPeriod == 'LTY') {
                $categoryBarData['year'][2] = end($yearArr);
                $categoryBarData['color'][end($yearArr)] = "#F03337";
                $categoryBarData['data'][end($yearArr)] = $categoryBarData1['data'][end($yearArr)];
                $categoryBarData['optimum'][end($yearArr)] = $categoryBarData1['optimum'][end($yearArr)];
            }
            //print_r($categoryBarData3);exit();
        }
        //echo "<pre>";print_r($categoryBarData);exit();
        if($compareWith == 1) {
           
            if($checkedPeriod == 'LTY')
                $data = $categoryBarData;
            else
                $data = $categoryBarData3;
        }
        else {
            $data = $categoryBarData;
        }
        
        return response()->json(['msg'=>'Expense data fetched successfully', 'data'=>$data, 'status' => Response::HTTP_OK]);
    }

    public function getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearArr, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isEmp="", $isLast2Year) {

        $colorCode   = [ "#9AB900", "#4BC0C0", "#FF6384", "#36A2EB", "#FF6384", "#F03337", "#9158ff"];

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
                    for ($i=$startYear; $i <= $endYear; $i++)
                        $yearArr[] = $i;
                } 
                else
                    $yearArr = [date('Y') - 2, date('Y') - 1];
            } else {
                for ($i=$startYear; $i <= $endYear; $i++)
                    $yearArr[] = $i;
            }
        }

        //$yearArr = array_unique($yearArr);

        $categoryBarData = []; 

        $categoryList           = Categories::where('deleted', 0)->get();
        $accountTypeArr         = ['Income'];//'Other Income'
        $netCollectionData      = $this->getNetCollectionData($accountTypeArr, $yearArr, $month, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year);       
        $nerOperatingIncomeData = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating='', $compareWith, $partCategory = '', $checkedPeriod, $isLast2Year);   
        $fetchNetOperatingIncome = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, 1, $compareWith, $partCategory = '', $checkedPeriod, $isLast2Year);

        $fetchNetOperatingIncome2 = $this->getNetOperatingIncomeData($yearArr, $month, $filterType, $fromDate, $toDate, $isNetOperating = '', $compareWith, $partCategory = 1, $checkedPeriod, $isLast2Year);
        
        $typesOfPractice        = Company::where('id', $company_id)->pluck('types_of_practice')->first();  

        if($isEmp == 1) {
            if(isset($categoryList)) { // Only 7 Categories to be shown on chart
                unset($categoryList[0]);unset($categoryList[1]);unset($categoryList[2]);unset($categoryList[3]);unset($categoryList[4]);unset($categoryList[5]);unset($categoryList[6]);unset($categoryList[7]);unset($categoryList[8]);unset($categoryList[9]);unset($categoryList[10]);unset($categoryList[11]);unset($categoryList[12]);unset($categoryList[13]);
            }
        } else {
            if(isset($categoryList)) { // Only 7 Categories to be shown on chart
                unset($categoryList[0]);unset($categoryList[6]);unset($categoryList[7]);unset($categoryList[8]);unset($categoryList[9]);unset($categoryList[10]);unset($categoryList[11]);
            } 
        }

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
        foreach ($categoryList as $category) {
            $categoryBarData['label'][]   = $category->name;

            foreach ($yearArr as $yearKey => $yearVal) {
                $categoryBarData['color'][$yearVal]  = $colorCode[$yearKey];
                //Apply Persentage Calculation

                if($category->id == 6) { //Employee Cost
                    if(isset($childEmpArr[$yearVal]))
                        if(isset($netCollectionData[$yearVal]))
                            $applyPerCal = ((($childEmpArr[$yearVal]) /$netCollectionData[$yearVal])*100);
                        else
                             $applyPerCal = 0;
                    else
                        $applyPerCal = 0;
                } 
                else {
                    if(isset($nerOperatingIncomeData[$category->id][$yearVal]))
                        $applyPerCal = ((($nerOperatingIncomeData[$category->id][$yearVal]) /$netCollectionData[$yearVal])*100);
                    else
                        $applyPerCal = 0;
                }


                if($category->id == 14) {
                    $netCollection = (isset($netCollectionData[$yearVal])) ? round($netCollectionData[$yearVal], 2) : 0;

                    $employeeCostExp = 0;
                    if(isset($childEmpArr[$yearVal])){
                        $employeeCostExp = $childEmpArr[$yearVal];
                    }

                    $totalOperatingExpenses = (isset($fetchNetOperatingIncome[$yearVal])) ? round($fetchNetOperatingIncome[$yearVal], 2) : 0 + $employeeCostExp;

                    if($netCollection != 0) {
                        $applyPerCal = ((($netCollection - $totalOperatingExpenses) / $netCollection)*100);
                    }
                    else {
                        $applyPerCal = 0;
                    }
                }


                $categoryBarData['data'][$yearVal][] = round($applyPerCal, 2);


                //OPTIMUM CALCULATIONS
                $getSpecalityPercentage = SpecialityPercentage::where('category_id', $category->id)->where('type_id', $typesOfPractice)->pluck('percentage')->first();
                $getValPersentage       = ($getSpecalityPercentage) ? ($getSpecalityPercentage) : '0';               

                //FOR AMOUNT CALCULATION
                //$getValPersentage       = ($getSpecalityPercentage) ? ($getSpecalityPercentage/100) : '0';  
                //$categoryBarData['optimum'][$yearVal][] = $getValPersentage*$netCollectionData[$yearVal];

                $categoryBarData['optimum'][$yearVal][] = $getValPersentage;
            }
        } 

        return $categoryBarData;
    }

    public function getNetCollectionData($accountTypeArr=[], $yearArr=[], $month, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isLast2Year) {
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

    public function getNetOperatingIncomeData($yearArr=[], $month, $filterType, $fromDate, $toDate, $isNetOperating='', $compareWith, $partCategory, $checkedPeriod, $isLast2Year) {
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
        ->where(function($query)use($company_id, $accountMethod, $yearArr, $partCategory) {
            $query->where([
                ['categories.deleted', '=', '0'],
                ['cm.deleted', '=', '0'],
                ['a.deleted', '=', '0'],
                ['ad.deleted', '=', '0'],
                //['a.account_type', 'Expense'],
                ['a.company_id', '=', $company_id],
                ['ad.company_id', '=', $company_id],
                ['ad.report_type', '=', $accountMethod],
            ]);
            //['a.account_type', 'Expense'],
            $query->whereIn('ad.year', $yearArr); 
            $query->whereIn('a.account_type', ['Expense', 'Cost of Goods Sold']); 
            $query->whereNotIn('categories.id' ,[6,7,8,9,10,11,12]);

            if($partCategory == 1) { //total other employee cost
                $query->whereIn('categories.id' ,[15,16,17,18,19]);
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
            if($checkedPeriod == 'LTY') {
                if($isLast2Year == 1) {
                    $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
                }
            } 
            else {
                $fetchAccountData->whereBetween('ad.date', [$fromDate, $toDate]);
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
            foreach ($fetchAccountData as $account) 
            {
                if($isNetOperating == 1)
                    $accountsArr[$account->year] = round($account->accountVal, 2);
                else
                    $accountsArr[$account->category_id][$account->year] = round($account->accountVal, 2);
            }
        }     

        return $accountsArr;
    }


    public function getEmployeeCostsExpenses(Request $request) {
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

        $netBarChartData = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isEmp=1, $isLast2Year="");

        if($compareWith == 1) {
            if($checkedPeriod == 'PY') {
                $fromDate = strtotime("last year", strtotime($fromDate));
                $fromDate = date('Y-m-d', $fromDate);
                $toDate = strtotime("last year", strtotime($toDate));
                $toDate = date('Y-m-d', $toDate);
            }
            else if($checkedPeriod == 'YTD') {
                $splitFromDate = explode("-", $fromDate);
                $fromDate = "2019-01-01";
                $splitToDate = explode("-", $toDate);
                $toDate = "2019-".$splitToDate[1].'-'.$splitToDate[2];
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

            $netBarChartData1 = $this->getoperationalExpensesReportData($company_id, $dateVal, $month, $CommaYear, $yearData, $filterType, $fromDate, $toDate, $compareWith, $checkedPeriod, $isEmp=1, $isLast2Year=1);

            $startYear = date('Y', strtotime($fromDate));
            $endYear = date('Y', strtotime($toDate));
            unset($yearData);
            for ($i=$startYear; $i <= $endYear; $i++) { 
                $yearData[] = $i;
            }

            $netBarChartData3['year'][1] = end($yearData);
            $netBarChartData3['color'][end($yearData)] = "#4BC0C0";
            $netBarChartData3['data'][end($yearData)] = $netBarChartData1['data'][end($yearData)];
            $netBarChartData3['optimum'][end($yearData)] = $netBarChartData1['optimum'][end($yearData)];

            if($checkedPeriod == 'LTY') {
                $netBarChartData['year'][2] = end($yearData);
                $netBarChartData['color'][end($yearData)] = "#F03337";
                $netBarChartData['data'][end($yearData)] = $netBarChartData1['data'][end($yearData)];
                $netBarChartData['optimum'][end($yearData)] = $netBarChartData1['optimum'][end($yearData)];
            }
            //print_r($netBarChartData3);exit();
        }
        //echo "<pre>";print_r($netBarChartData);exit();
        if($compareWith == 1) {
            if($checkedPeriod == 'LTY')
                $data = $netBarChartData;
            else
                $data = $netBarChartData3;
        }
        else {
            $data = $netBarChartData;
        }

        return response()->json(['msg'=>'Employee data fetched successfully', 'data'=>$data, 'status' => Response::HTTP_OK]);

    }
}






