<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Models\GlobalReportList;
use App\Http\Controllers\QuickbooksController;
use App\Http\Controllers\defaultWriterController;
//use App\Models\CustomReportGroup;
//use App\Models\GlobalReportGroup;
use App\Models\Accounts;
use App\Models\AccountsData;
//use App\Models\ReportWriterDetails;
//use App\Models\ReportWriter;
//use App\Models\ReportGroupConfig;
//use App\Models\ReportConfigure;
//use App\Models\ReportWriterColumn;
//use App\Models\ReportPackage;

use App\Models\UserCompanyMap;

use Auth;

class ReportsController extends Controller
{
    /*private $defaultHelper;

    public function __construct()
    {
        $this->defaultHelper = new defaultWriterController();
    }*/

    public function getQBOReports()
    {
        $count = isset($_GET['count']) ? $_GET['count'] : "";

        $resArray['error'] = false;

        if($count >= 0 && $count <= 3 ){

            $i = $count;

           if($i == 0){
                $start = date('Y-m-d', strtotime('first day of January'));  
                $end   = date('Y-m-d', strtotime('last day of December'));
                $year  = date('Y');
           } else {
                $start = date('Y-m-d', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('first day of January')) ))); 
                $end   = date('Y-m-d', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('last day of December')) ))); 
                $year = date('Y', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('first day of January')) ))); ;
           }

           $resArray['start'] = $start;
           $resArray['end']   = $end;
           $resArray['year']  = $year;
           //print_r($resArray);exit();
           $r_data = $this->fetchQBOReports($start, $end, $year, '', $count);
           
           if($year == 2019 || $year == '2019')
            $resArray['r_data']  = $r_data;
        }

        return $resArray;
    }

    public function fetchQBOReports($start, $end, $mainYear, $accId = "", $count)
    {
        if(\Session::has('new_practices_flow')) { 
            $userObj = \Session::get('company_qbo');
        }else {
            $userObj = get_current_userObj();
        }

        //print_r($userObj['company_id']);exit();
        /*if($count == 0)
            AccountsData::where('company_id', $userObj['company_id'])->delete();*/

        //$method = 'Accrual';
        //$methodObj  = ReportConfigure::where('company_id', $userObj['company_id'])->where('active', 1)->first();
        //$method     = 'Accrual';isset($methodObj) && ($methodObj->report_method == 1) ? 'Cash' : 'Accrual';

        $obj = new QuickbooksController();
       
        $data1 = $obj->importReports($start, $end, $method = 'Accrual', $accId, 'pl');
        //print_r($data1);
        $data2 = $obj->importReports($start, $end, $method = 'Cash', $accId, 'pl');

        $newAr1 = $this->formBalanceSheetArray($data1, $mainYear, $report_type = 1);
        $newAr2 = $this->formBalanceSheetArray($data2, $mainYear, $report_type = 2);
        $myNewArray = $newAr1;//array_merge($newAr1, $newAr2);         

        for ($i=1; $i < 3; $i++)
        { 
            if($i== 1)
                $commonArr = $myNewArray;
            else
                $commonArr = $newAr2;

            $this->saveReportValues($i, $commonArr, $userObj['company_id'], $mainYear, $accId);
        }

        return $myNewArray;
    }

    public function recursiveArray($MainArray, $storage = [])
    {
        $rows = isset($MainArray->Rows->Row) ? $MainArray->Rows->Row : [];

        //echo "<pre>";print_r($rows);exit();

        foreach ($rows as $key => $value) {
          
            if(isset($value->type) && $value->type == 'Data' && isset($value->ColData)){
                $colData = $value->ColData;
                $storage[] = $colData;
            } elseif(isset($value->Rows->Row)) {
               
                if(isset($value->Header->ColData)){
                    $head = $value->Header->ColData;
                    if(isset($head) && isset($head[0]->id)){
                        $storage[] = $head;
                    }
                }
                $storage1 = $this->recursiveArray($value, $storage);
                $storage = $storage1;
            }
        }

        return $storage;  
    }

    public function formBalanceSheetArray($data, $year, $report_type)
    {
        $result = []; $allData = []; 

        //echo "Hello<pre>";print_r($data);exit();
        if(isset($data) && !$data['error']){
            $topColumn = $data['data']->Columns;
            $rows = $data['data']->Rows;

            if (isset($rows->Row)) 
            {
                //echo "Hello<pre>";print_r($data['data']);exit();
                $allData1 = $this->recursiveArray($data['data']);

                $allData = array_merge($allData, $allData1);
                //echo "Hello<pre>";print_r($allData);exit();
                       
            }
        }

        $columnVal = [];
        if(isset($data['data'])) {
            $columns = $data['data']->Columns->Column;
            foreach ($columns as $Ckey => $Cvalue) {
                if($Ckey > 0) {
                    //echo "Hello<pre>";print_r($Cvalue->ColTitle);exit();
                    
                    if($Cvalue->ColTitle != 'Total') {
                        $convertDate = date('d-m-Y',strtotime($Cvalue->ColTitle));
                        $columnVal[] = $convertDate;
                    }
                   
                }
            }
        }

        $monthArr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        $myNewArray = [];

        foreach ($allData as $key => $value) {
            //echo "Hello12<pre>";print_r($value);exit();
            if(isset($value[0]->id))
            {
                $name = $value[0]->value;
                $id = $value[0]->id;

                $pattern = $id.'_'.$year;

                $myNewArray[$pattern]['name']           = $name;
                $myNewArray[$pattern]['id']             = $id;
                $myNewArray[$pattern]['summary_name']   = '';
                $myNewArray[$pattern]['summary_value']  = 0;
                $myNewArray[$pattern]['date']           = '';
                $myNewArray[$pattern]['year']           = $year;
                $myNewArray[$pattern]['report_type']    = $report_type;
                

               /* foreach ($monthArr as $Mkey => $Mvalue) 
                {
                    $searchKey = $Mkey + 1;
                    $amount = isset($value[$searchKey]->value) ? $value[$searchKey]->value : 0;
                    $myNewArray[$pattern][$Mvalue]         = $amount;
                }*/

                foreach ($columnVal as $Vkey => $Vvalue) {
                    $searchKey = $Vkey + 1;
                    $amount = isset($value[$searchKey]->value) ? $value[$searchKey]->value : 0;

                    if(!empty($amount)) {
                        $myNewArray[$pattern]['date_month'][$Vvalue] = $amount;
                    }
                    
                }


            }
            
        }
        //echo "He<pre>";print_r($myNewArray);exit();
        return $myNewArray;
    }

    public function saveReportValues($isFirst, $reportArray, $company_id, $mainYear, $accId = "")
    {
        /*$updateArray = array('jan' => 0, 'feb' => 0, 'mar' => 0, 'apr' => 0,
                             'may' => 0, 'jun' => 0, 'jul' => 0, 'aug' => 0,
                             'sep' => 0, 'oct' => 0, 'nov' => 0, 'dec' => 0,
                             'deleted' => 1, 'deleted_at' => date('Y-m-d H:i:s')
                             );*/

        $query = AccountsData::where('company_id', $company_id)
                              ->where('deleted', 0)
                              ->where('year', $mainYear);

        if($accId != ""){
            $allId = explode(',', $accId);

            if(count($allId) > 0)
              $query->whereIn('qbo_id', $allId);
        }
      
        $monthArr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        foreach ($reportArray as $key => $value) {    

           if(isset($value['date_month'])) {
               foreach ($value['date_month'] as $Dkey => $Dvalue) {

                    /*$accountSave = AccountsData::create([
                      'company_id'        => $company_id,
                      'qbo_id'            => $value['id'],
                      'year'              => $value['year'],
                      'report_type'       => $value['report_type'],
                      'values'            => $Dvalue,
                      'date'              => date('Y-m-d',strtotime($Dkey)),
                      'deleted'           => 0,
                    ]);*/

                    $AccData = AccountsData::firstOrNew(array('company_id' => $company_id, 'qbo_id' => $value['id'], 'report_type' => $value['report_type'], 'date' => date('Y-m-d',strtotime($Dkey))));
                    $AccData->deleted = 0;
                    $AccData->deleted_at = null;
                    $AccData->year = $value['year'];
                    $AccData->values = $Dvalue;
                    $AccData->save();               
               }
            }
           //echo "DateMonth<pre>";print_r($AccData);exit();
        }
    }

    public function getCronQBOReports($count, $company_id)
    {
        //$count = isset($_GET['count']) ? $_GET['count'] : "";

        $resArray['error'] = false;

        if($count >= 0 && $count <= 3 ){

            $i = $count;

           if($i == 0){
                $start = date('Y-m-d', strtotime('first day of January'));  
                $end   = date('Y-m-d', strtotime('last day of December'));
                $year  = date('Y');
           } else {
                $start = date('Y-m-d', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('first day of January')) ))); 
                $end   = date('Y-m-d', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('last day of December')) ))); 
                $year = date('Y', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('first day of January')) ))); ;
           }

           $resArray['start'] = $start;
           $resArray['end']   = $end;
           $resArray['year']  = $year;
           //print_r($resArray);exit();
           $r_data = $this->fetchCronQBOReports($start, $end, $year, '', $company_id, $count);
           
           if($year == 2019 || $year == '2019')
            $resArray['r_data']  = $r_data;
        }

        return $resArray;
    }

    public function fetchCronQBOReports($start, $end, $mainYear, $accId = "", $company_id, $count)
    {
        //$method = 'Accrual';
        //$methodObj  = ReportConfigure::where('company_id', $userObj['company_id'])->where('active', 1)->first();
        //$method     = 'Accrual';isset($methodObj) && ($methodObj->report_method == 1) ? 'Cash' : 'Accrual';

        if($count == 0)
            AccountsData::where('company_id', $company_id)->delete();

        $obj = new QuickbooksController();
       
        $data1 = $obj->importCronReports($start, $end, $company_id, $method = 'Accrual', $accId, 'pl');
        $data2 = $obj->importCronReports($start, $end, $company_id, $method = 'Cash', $accId, 'pl');

        $newAr1 = $this->formBalanceSheetArray($data1, $mainYear, $report_type = 1);
        $newAr2 = $this->formBalanceSheetArray($data2, $mainYear, $report_type = 2);

        $myNewArray = $newAr1;//array_merge($newAr1, $newAr2); 

        for ($i=1; $i < 3; $i++)
        { 
            if($i== 1)
                $commonArr = $myNewArray;
            else
                $commonArr = $newAr2;

            $this->saveReportValues($i, $commonArr, $company_id, $mainYear, $accId);
        }


        return $myNewArray;
    }

    public function getFixedIncomeStatementData($report_id, $rw, $last_day_month = "")
    {
        $data['header']        = [];
        $data['report_writer'] = []; 

         $columnArray = [];
        if(\Session::has('new_practices_flow')) { 
            $userObj = \Session::get('company_qbo');
        } else {
            $userObj = get_current_userObj();
        }

        if($last_day_month != "")
            $as_date = $last_day_month;
        else
            $as_date = isset($rw) && isset($rw->as_on_date) ? $rw->as_on_date : date('Y-m-01');

        $dates = array($as_date);

        $columns = isset($rw)? $rw->columns : [];

        $colStyles = [];

        foreach ($columns as $key => $value) {
            if($value->column_type == 1){
                $periods = explode(',', $value->period);

                foreach ($periods as $pkey => $pvalue) {
                    $format = $pvalue." months";
                    $columnArray[$value->order]['dates'][] = $dates[] = date ("Y-m-d", strtotime ($as_date .$format)); 
                    $columnArray[$value->order]['format_dates'][] = lcfirst(date('M_Y', strtotime(  date ("Y-m-d", strtotime ($as_date .$format)) ))); 
                }

                $columnArray[$value->order]['type'] = 1;
            } else {
                $columnArray[$value->order]['type'] = 2;
                $columnArray[$value->order]['operation'] = $value->operation;
                $columnArray[$value->order]['name'] = $value->column_name;
            }

            $colStyles[$value->order]['style'] = $value->style;
            $colStyles[$value->order]['format'] = $value->format;
            $colStyles[$value->order]['rw_precision'] = $value->rw_precision;
            $colStyles[$value->order]['is_override'] = $value->is_override;
        }


        $minDate = $maxDate = "";

         if(sizeof($dates) > 0){
            usort($dates,
                function ($a, $b)
                {
                    $dateTimestamp1 = strtotime($a);
                    $dateTimestamp2 = strtotime($b);
                    return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
                });
            $minDate = $dates['0'];
            $maxDate = $dates[count($dates) - 1];
        }

        $monthArr  = getMonthsInRange($minDate, $maxDate, 1);

        $getReportData    = $this->getIncomeStatementData($monthArr, $report_id);

        $newHeader   = [];  $typeOneArr = []; $operationArr = [];

        foreach ($columnArray as $key => $typeArr) 
        {
            if($typeArr['type'] == 1){
                $typeOneArr[$key] = $typeArr;
            }
            else
            {
                $operationArr[$key] = $typeArr;
            }
        }

        $columnArray = array_merge($typeOneArr, $operationArr);            

        foreach ($columnArray as $Newkey => $Newvalue) {
            $newHeader[$Newkey] = $Newkey;

            if($Newvalue['type'] == 1){

                $newDates = $Newvalue['dates'];

                usort($newDates,
                    function ($a, $b)
                    {
                        $dateTimestamp1 = strtotime($a);
                        $dateTimestamp2 = strtotime($b);
                        return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
                    });
                $NewminDate = $newDates['0'];
                $NewmaxDate = $newDates[count($newDates) - 1];

                $NewFormat_minDate = lcfirst(date('M_Y', strtotime($NewminDate)));
                $NewFormat_maxDate = lcfirst(date('M_Y', strtotime($NewmaxDate)));

                if($NewFormat_minDate == $NewFormat_maxDate){
                    $newHeader[$Newkey] = ucfirst(date('M y', strtotime($NewminDate)));
                } else {
                    $newHeader[$Newkey] = ucfirst(date('M y', strtotime($NewminDate)))." - ".ucfirst(date('M y', strtotime($NewmaxDate)));;
                   // $newHeader[$Newkey] = ucfirst(date('M y', strtotime($NewmaxDate)))." - ".ucfirst(date('M y', strtotime($NewminDate)));;
                }

                foreach ($getReportData['report_writer'] as $key => $value) {
                    $data_details = isset($value->data_details) ? $value->data_details : [];

                    $sumValue = 0; $start = 0;
                    foreach ($data_details as $fkey => $fvalue) {

                        if($fkey == $NewFormat_minDate){
                            $start = 1;
                        }

                        if($start == 1){
                            $sumValue += $fvalue;
                        }

                        if($fkey == $NewFormat_maxDate){
                            $start = 0;
                            break;
                        }

                    }

                    if(!isset($value['data_fixed_details']))
                        $value['data_fixed_details'] = [];

                    $value['data_fixed_details'] = array_merge($value['data_fixed_details'], array($Newkey => $sumValue));
                }
           }
           else {  // type == 2 

                $operation = $Newvalue['operation'];
                $name = $Newvalue['name'];
                $newHeader[$Newkey] = $name;
                $expOper = explode(',', $operation);

                foreach ($getReportData['report_writer'] as $key => $value) {
                    $data_fixed_details = isset($value->data_fixed_details) ? $value->data_fixed_details : [];

                     $sumValue = 0;
                   
                   $val1 = isset($data_fixed_details[$expOper[0]]) ? $data_fixed_details[$expOper[0]] : 0;
                   $val2 = isset($data_fixed_details[$expOper[2]]) ? $data_fixed_details[$expOper[2]] : 0;
                   $operation = isset($expOper[1]) ? $expOper[1] : '+';
 
                    if($operation == '+'){
                        $sumValue = $val1 + $val2;
                    }
                    elseif ($operation == '-') {
                        $sumValue = $val1 - $val2;
                    }
                    elseif ($operation == '*') {
                        $sumValue = $val1 * $val2;
                    }
                    elseif ($operation == '/') {
                        if($val2 != 0)
                            $sumValue = $val1 / $val2;
                    }  

                    //Calculate Percent for value A and B, FORMULA = (((val1-val2) / val1 ) * 100 )
                    if($colStyles[$Newkey]['format'] == '1' && $val1 != '0' && $val2 != '0')
                    {
                        $sumValue = calPercent($sumValue, $val2);
                    }               
                    
                    $value['data_fixed_details'] = array_merge($value['data_fixed_details'], array($Newkey => $sumValue));
                }
           }
        }

        $sortHeader         = $newHeader;
        ksort($sortHeader);        

        foreach ($getReportData['report_writer'] as $key => $value) {
            $value->data_details = $value['data_fixed_details'];
        }

        $getReportData['header']        = $sortHeader;
        $getReportData['colstyle']      = $colStyles;

        return $getReportData;
    }

    public function formResultArray($sumArray, $details, $operator, $header)
    {
       $res = [];

       foreach ($header as $key => $value) {
           $val1 = isset($sumArray[$key]) ? $sumArray[$key] : 0;
           $val2 = isset($details[$key]) ? $details[$key] : 0;

           if($operator == 0 || $operator == 1){
               $res[$key] = $val1 + $val2;
           }
           elseif ($operator == 2) {
               $res[$key] = $val1 - $val2;
           }
           elseif ($operator == 4) {
               if($val2 != 0)
                 $res[$key] = $val1 / $val2;
               else
                 $res[$key] = 0;
           }
           elseif ($operator == 3) {
               $res[$key] = $val1 * $val2;
           } else {
               $res[$key] = $val1 + $val2;
           }
       }

       return $res;
    }

    public function formFiscalyearHeader($header, $startMonth)
    {
        $result = [];
        foreach ($header as $key => $value) {
            $result[$key] = $this->formFiscalMonth($key, $startMonth);
        }

        return $result;
    }

    public function formFiscalMonth($year, $start = 1)
    {
        $monthArr = array('jan', 'feb', 'mar', 'apr', 'may', 'jun',
                          'jul', 'aug', 'sep', 'oct', 'nov', 'dec');

        $array = []; $actualStart = $start - 1; $head = ucfirst($monthArr[$actualStart]).' '.date('y', strtotime('01-01-'.$year));

        for ($i=0; $i < 12; $i++) { 
            $array[] = $monthArr[$actualStart].'_'.$year;
            $actualStart++;
            if($actualStart > 11){
                $actualStart = 0;
                $year++;
            }
        }

        $actualStart = $actualStart - 1;

        if($start == 1){
            $year -= 1;
        }

        if($actualStart < 0)
            $actualStart = 11;

        $head .= ' - '.ucfirst($monthArr[$actualStart]).' '.date('y', strtotime('01-01-'.$year));

        return ['value' => $array, 'header' => $head];
    }

    public function formGlbalGroupArray($GlopCusArray, $reports_data, $accId, $type, $header, $period = 'month', $piscalHeader = [])
    {
        $result = [];

        $monthArr = array('jan', 'feb', 'mar', 'apr', 'may', 'jun',
                          'jul', 'aug', 'sep', 'oct', 'nov', 'dec');        

        if($type == 1){
           $pattern = 'global_'.$accId;
           $all_acc_ids = isset($GlopCusArray['global'][$pattern]) ? $GlopCusArray['global'][$pattern] : [];
        } elseif($type == 2){
           $pattern = 'cus_'.$accId;
           $all_acc_ids = isset($GlopCusArray['custom'][$pattern]) ? $GlopCusArray['custom'][$pattern] : [];
        } elseif($type == 3 || $type == 4){
           $all_acc_ids = array($accId);
        }else {
           $all_acc_ids = [];
        }

        $incomeOrBalance = $this->checkIncomeOrBalanceType($type, $accId);

        foreach ($all_acc_ids as $key => $value) {
                $search = 'qbo_'.$value;
                $data = isset($reports_data[$search]) ? $reports_data[$search] : [];

                foreach ($data as $newkey => $newvalue) {

                    if(isset($newvalue) && isset($newvalue->year)){

                        $year = $newvalue['year']; 

                        foreach ($monthArr as $key1 => $value1) {
                            $match = $value1.'_'.$year;
                                
                            if(array_key_exists($match, $result)){
                                $result[$match] += $newvalue[$value1];
                            } else {
                                $result[$match] = $newvalue[$value1];
                            }
                        }
                    }
                }
            }

        $yearRes = [];

        if($period == 'year'){
            
            foreach ($piscalHeader as $key => $value) {
                $yearRes[$key] = 0;
               foreach ($value['value'] as $key1 => $value1) {
                  if($incomeOrBalance == 1)
                    $yearRes[$key] += isset($result[$value1]) ? $result[$value1] : 0; 
                  else
                    $yearRes[$key] = isset($result[$value1]) ? $result[$value1] : 0; 
               }
            }

            $result = $yearRes;

        }
        

        /*if($period == 'year'){
            foreach ($header as $key => $value) {
                $yearRes[$key] = 0;
                foreach ($monthArr as $key1 => $value1) {
                    $match = $value1.'_'.$key;
                    if($incomeOrBalance == 1)
                        $yearRes[$key] += isset($result[$match]) ? $result[$match] : 0; 
                    else
                        $yearRes[$key] = isset($result[$match]) ? $result[$match] : 0; 
                }
            }
            $result = $yearRes;
        }*/

        
             
        $result = $this->matchHeaderToArray($result, $header);

        if($period==2){
            $newRes = 0;
            foreach ($result as $key => $value) {
                if($incomeOrBalance == 1)
                  $newRes += $value;
                else
                  $newRes = $value;   
            }
            $new_result[$key] = $newRes;
            $result = $new_result;
        }


        return $result;
    }

    public function matchHeaderToArray($array, $header)
    {
        $result = [];

        foreach ($header as $key => $value) {
           $result[$key] = isset($array[$key]) ? $array[$key] : 0;
        }

        return $result;
    }

    public function getReportsData($accounts, $company_id)
    {
       $result = [];

       $data = AccountsData::from('accounts_data as ad')
                              ->join('accounts as a', 'a.qbo_id', '=', 'ad.qbo_id')
                              ->select('ad.*','a.account_name')
                              ->where('ad.company_id', $company_id)
                              ->where('a.company_id', $company_id)
                              ->where('ad.deleted', 0)
                              ->whereIn('ad.qbo_id', $accounts)->get();

        foreach ($data as $key => $value) {
            $result['qbo_'.$value->qbo_id][] = $value;
        }

        return $result;
    }

    public function getMoreAccounts($global, $custom, $company_id)
    {
        $global = implode(',', array_filter($global)); 
        $custom = implode('|', array_filter($custom)); 

        $moreAcc = [];


        if($global == "" && $custom != ""){
            $data = \DB::select('SELECT * FROM `reporting_group_config` WHERE `company_id` = '.$company_id.' AND `deleted` = 0 AND CONCAT(",", `custom_group_id`, ",") REGEXP ",('.$custom.'),"');
        }

        if($custom == "" && $global != ""){
            $data = \DB::select('SELECT * FROM `reporting_group_config` WHERE `company_id` = '.$company_id.' AND `deleted` = 0 AND `global_group_id` IN ('.$global.')');
        }

        if($global == "" && $custom == ""){
            $data = [];
        }

        if($global != "" && $custom != ""){
            $data = \DB::select('SELECT * FROM `reporting_group_config` WHERE `company_id` = '.$company_id.' AND `deleted` = 0 AND `global_group_id` IN ('.$global.') OR CONCAT(",", `custom_group_id`, ",") REGEXP ",('.$custom.'),"');
        }

        foreach ($data as $key => $value) {
            $moreAcc[] = $value->qbo_id;
        }

        $result['more_acc'] = $moreAcc;
        $result['data'] = $data;

        $global = []; $custom = [];

        foreach ($data as $key => $value) {
            if($value->global_group_id > 0){
              $global['global_'.$value->global_group_id][] = $value->qbo_id;
            }

            if($value->custom_group_id != ""){
                $exp = explode(',', $value->custom_group_id);
                foreach ($exp as $key1 => $value1) {
                  $custom['cus_'.$value1][] = $value->qbo_id;
                }
            }
        }

        $result['global'] = $global;
        $result['custom'] = $custom;

        return $result;
    }

    public function formYOYMonthArray($month, $year, $previousyear)
    {
        $result = [];

        while ($previousyear <= $year) {
            $format = $previousyear."-".$month."-01";
            $result[lcfirst(date('M_Y', strtotime($format)))] = date('M y', strtotime($format));
            $previousyear++;
        }

        return $result;
    }

    public function addNewColumn(Request $request)
    {
       $data = $request->dataArr; 

       return view('reports.add_column')->with($data);
    }
}