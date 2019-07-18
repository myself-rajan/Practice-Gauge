<?php
if (! function_exists('get_income_account_types_array')) {
  function get_income_account_types_array()
  {
    $defined_income_types = ['Income', 'Other Income', 'Cost of Goods Sold', 'Expense', 'Other Expense'];
    return $defined_income_types;
  }
}

if (! function_exists('get_current_userObj')) {
  function get_current_userObj($company_id='')
  {
    if(!Auth::check() && $company_id != '')
    {
      \Session::put('company', ['company_id' => $company_id]);
      $result['company_id'] = $company_id;
      return $result;
    }

    if(Auth::check())
    {
      $result['user_id'] = Auth::user()->id;
    }

    $result['company_id'] = \Session::get('company')['company_id'];
    return $result;
  }
}

if (! function_exists('getRoleBasedMenu')) {
  function getRoleBasedMenu($role)
  {
     $result = [];

     if($role == 3 || $role == 7){
        $result = array(1, 11, 47, 48, 50, 51); // home and admin menu
     }

     if($role == 5){
        $result = array(1, 4, 5, 19, 21, 22, 23, 24, 25); // Reports and dash menu
     }

     if($role == 9){
        $result = array(1, 4, 5, 11, 19, 21, 22, 23, 24, 25, 48, 51); // Reports and dash menu
     }


     if($role == 4){
        $result = array(1, 11, 47, 48, 49); // home and admin menu company group
     }

     return $result;
  }
}

if (! function_exists('pmode')) {
  function pmode($data, $is_end = 1)
  {
     echo "<pre>";
     print_r($data);
     echo "<br>";

     if($is_end == 1){
      die('end of print mode');
     }
  }
}

if (! function_exists('get_balance_sheet_types_array')) {
  function get_balance_sheet_types_array()
  {
     $defined_bs_types = ['Accounts Payable', 'Accounts Receivable', 'Bank', 'Credit Card', 'Equity', 'Fixed Asset', 'Long Term Liability', 'Other Current Liability', 'Other Current Asset', 'Other Assets'];

     return $defined_bs_types;
  }
}

if (! function_exists('getRowClass')) {
  function getRowClass($style='', $format='', $makeComma='')
  {
    $styleArr    = explode(",", $style);
    $rowClassArr = array();

    //Check Row Class
    if (in_array('1', $styleArr)) //Bold
    {
      array_push($rowClassArr, "rw-format-bold");
    }

    if (in_array('2', $styleArr)) //Italic
    {
      //array_push($rowClassArr, "rw-format-bold");
    }

    if (in_array('3', $styleArr)) //Single Underline
    {
      array_push($rowClassArr, "rw-format-underline");
    }

    if (in_array('4', $styleArr)) //Single Underline
    {
      array_push($rowClassArr, "rw-format-underline-double");
    }

    if (in_array('5', $styleArr)) //Single Underline
    {
      array_push($rowClassArr, "rw-format-box");
    }

    if (in_array('10', $styleArr)) //Single Underline
    {
      array_push($rowClassArr, "rw-format-up-underline");
    }

    if($format == 1) //Percent
    {
      array_push($rowClassArr, "rw-format-percentage");
    }

    if ($format == 3) //Multiple
    {
      array_push($rowClassArr, "rw-format-multiple");
    }

    if($makeComma == ''){      
      return implode(" ", $rowClassArr);
    }else
    {
      return implode(",", $rowClassArr);
    } 
  }
}

if (! function_exists('getColumnWiseClass')) {
  function getColumnWiseClass($style=0, $format=2, $makeComma='') 
  {
    $styleArr    = explode(",", $style);
    $colClassArr = array();

    if(in_array('1', $styleArr)) //Bold
    {
      array_push($colClassArr, "cw-format-bold");
    } else {
      array_push($colClassArr, "cw-format-no-bold");
    }

    if(in_array('3', $styleArr)) //single underline
    {
      array_push($colClassArr, "cw-format-underline");
    } else {
      array_push($colClassArr, "cw-format-no-underline");
    }

    if(in_array('4', $styleArr)) //double underline
    {
      array_push($colClassArr, "cw-format-underline-double");
    } else {
      array_push($colClassArr, "cw-format-no-underline-double");
    }

    if(in_array('5', $styleArr)) //box
    {
      array_push($colClassArr, "cw-format-box");
    }

    if($format == 1) //Percent
    {
      array_push($colClassArr, "cw-format-percentage");
    }

    if ($format == 3) //Multiple
    {
      array_push($colClassArr, "cw-format-multiple");
    }    

    if($format == 2){
      array_push($colClassArr, "removeContent");
    }

    if($makeComma == ''){      
      return implode(" ", $colClassArr);
    }else
    {
      return implode(",", $colClassArr);
    }
  }
}

if (! function_exists('getMonthsInRange')) {
  function getMonthsInRange($startDate, $endDate, $i = 0) 
  {
    $months = array(); 
    while (strtotime($startDate) <= strtotime($endDate)) {
      if($i != 0)
        $months[lcfirst(date('M_Y', strtotime($startDate)))] = date('M y', strtotime($startDate));
        $startDate = date('d M Y', strtotime($startDate.
            '+ 1 month'));
        $i++;
    }

    return $months;
  }
}

if (! function_exists('getYearInRange')) {
  function getYearInRange($start, $end) 
  {
    $years = array(); 

    while ($start <= $end) {
      $years[$start] = $start;
      $start++;
    }

    return $years;
  }
}

if (! function_exists('dec_enc')) {
  function dec_enc($action, $string) {
      $output = false;
   
      $encrypt_method = "AES-256-CBC";
      $secret_key     = 'smarT!@#123';
      $secret_iv      = 'reporT!@#123';
   
      // hash
      $key = hash('sha256', $secret_key);
      
      // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
      $iv = substr(hash('sha256', $secret_iv), 0, 16);
   
      if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = substr(base64_encode($output), 0, -2);
      }
      else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
      }
   
      return $output;
  }
}

if (! function_exists('getPerviousRoute')){
  function getPerviousRoute()
  {
    $perviousUrl   = url()->previous();
    $reversedParts = explode('/', $perviousUrl, 6);
    $report_id     = isset($reversedParts['5']) ? $reversedParts['5'] : ''; 
    $group_type    = '';

    if($report_id){
      $dec_report_id  = dec_enc('decrypt', $report_id);    
      $group_type     = App\Models\GlobalReportList::where('id', $dec_report_id)->pluck('group_type')->first();
    }

    return $group_type;
  }
}

if(! function_exists('calPercent')){
  function calPercent($sumvalue, $val2)
  {
    $result = (($sumvalue / $val2 ) * 100 );

    return $result;
  }
}

if (! function_exists('getRoutes')){  
  function getRoutes()
  {
    $allRoutes = []; 

    foreach (\Route::getRoutes()->getRoutes() as $route) {
        $action = $route->getAction();
        if (array_key_exists('as', $action)) {
            $commonRoutes[] = $action['as'];
        }
    }

    $allRoutes['common_routes']       = array_slice($commonRoutes, 14, 2);  //14 to 15
    $allRoutes['view_all_companies']  = array_slice($commonRoutes, 16, 25); //16 to 39
    $allRoutes['report_config']       = array_slice($commonRoutes, 41, 2);  //41 to 40
    $allRoutes['report_list']         = array_slice($commonRoutes, 43, 11); //43 to 51
    $allRoutes['user_list']           = array_slice($commonRoutes, 54, 11); //54 to 64
    $allRoutes['company_group']       = array_slice($commonRoutes, 65, 10); //65 to 74
    $allRoutes['view_report_package'] = array_slice($commonRoutes, 75, 11); //70 to 80
    $allRoutes['redirect_global']     = array_slice($commonRoutes, 86, 1); //70 to 80

    return $allRoutes;
  }
}

if (! function_exists('roleBasedCompanySessionClear')){
  //Clear company session data for Global Company Administrator
  function roleBasedCompanySessionClear()
  {
    $result['error'] = false;

    $roleArr = explode(",", Auth::user()->role);       

    if(Auth::check() && (in_array('3', $roleArr))){
      Session::put('company', [ 'qbo_connection' => 0, 'company_id' => 0, 'global_company_name' => 0, 'global_company_logo' => 0]);
    }

    return $result;
  }
}

if (! function_exists('removeSpecialChar')){
  function removeSpecialChar($string) {
    return preg_replace('/[^A-Za-z0-9\- ]/', '', $string); 
  }
}

if (! function_exists('getReportArr')){
  function getReportArr($package_id, $count='', $package='')
  {
    $getReportData  = App\Models\ReportPackageDetails::where('package_id', $package_id)->where('deleted', 0)->get();

    foreach ($getReportData as $report) {
      $getreportArr[]                      = $report->report_id;
      $getPackageArr[$report->report_id]   = $report->period_id.",".$report->period_sequence.",".$report->trailing_periods.",".$report->include_total;
    }

    $reportArr  = !empty($getreportArr) ?  $getreportArr : [];
    $packageArr = !empty($getPackageArr) ?  $getPackageArr : [];

    if($count)
      return count($reportArr);
    else if($package)
      return $packageArr;
    else
      return $reportArr;
  }
}