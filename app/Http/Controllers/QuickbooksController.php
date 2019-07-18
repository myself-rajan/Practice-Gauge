<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

// added for reports
use QuickBooksOnline\API\Facades\Purchase;
use QuickBooksOnline\API\Data\IPPPurchase;
use QuickBooksOnline\API\QueryFilter\QueryMessage;
use QuickBooksOnline\API\ReportService\ReportService;
use QuickBooksOnline\API\ReportService\ReportName;
use Illuminate\Support\Facades\Mail;

use App\Models\Company;
use App\Models\User;
use App\Models\UserCompanyMap;

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ReportsController;

use Auth;
use Session;

class QuickbooksController extends Controller
{

  public $dataService;

  public function __construct() {
        //print_r(\Session::get('company'));exit();
        $this->dataService   =  DataService::Configure(array(
          'auth_mode'    => 'oauth2',
          'ClientID'     => \Config::get('quickbooks.client_id'),
          'ClientSecret' => \Config::get('quickbooks.client_secret'),
          'RedirectURI'  => asset("/qbo/callback"),
          'scope'        => "com.intuit.quickbooks.accounting",
          'baseUrl'      => \Config::get('quickbooks.base_url'),
        ));   
    }

    public function writeErrorLogQB($message, $user_id='')
    {

      if($user_id == "" && Auth::check()){
        $user_id = Auth::user()->id;
      }

      if(!is_array($message)){
        $text = "\n"."Time : ".date('m/d/y H:i:s').", User id : ".$user_id.", Message : ".$message."\n";
      } else {
        $text = "\n"."Time : ".date('m/d/y H:i:s').", User id : ".$user_id.", Message : \n";
      }   
      $file = public_path('qboerrorlog.txt');
      $myfile = fopen($file, "a");

      fwrite($myfile,  print_r($text, TRUE));
    }

    public function getUserAndCompanyId($compId = "")
    {
      /*if($compId == "" && Auth::check()){
        $result['user_id'] = Auth::user()->id;*/
      if(\Session::has('new_practices_flow')) {
        $result['user_id'] = \Session::get('company_qbo')['user_id'];
        $result['company_id'] = \Session::get('company_qbo')['company_id'];
      }
      else if($compId == "" && Auth::check()) {
        $result['user_id'] = Auth::user()->id;
        $result['company_id'] = \Session::get('company')['company_id'];
      } 
      else {
        $result['company_id'] = $compId;
        $result['user_id'] = UserCompanyMap::where('company_id', $compId)->first();//->user_id
      }
       
       return $result;
    }

    public function get_all_token($compId = "")
    {
      

      if($compId == ""){
    
        if(\Session::has('new_practices_flow')) 
        {
          $Obj = \Session::get('company_qbo')['company_id'];
        } 
        else {
          $userObj = $this->getUserAndCompanyId();
          $Obj = $userObj['company_id'];
        }

        $check = Company::where('id', $Obj)->first();

      } else {
        $check = Company::where('id', $compId)->first();
      }

      /*if($compId == ""){
        $userObj = $this->getUserAndCompanyId();
        $check = Company::where('id', $userObj['company_id'])->first();
      } else {
        $check = Company::where('id', $compId)->first();
      }*/
       $result['qbo_realmid'] = $check->qbo_realmid;
       $result['qbo_access_token'] = $check->qbo_access_token;
       $result['qbo_refresh_token'] = $check->qbo_refresh_token;
       $result['qbo_connection'] = $check->qbo_connection;

       return $result;
    }

    public function getOauth2Url()
    {
        $url = '';

        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $url = $OAuth2LoginHelper->getAuthorizationCodeURL();

        return $url;
    }

   public function getQBOIntegrations()
   {

    \View::share('global_page_title', 'QuickBooks Online Integration');

    $qbo_auth_url = $this->getOauth2Url(); 

    $allToken = $this->get_all_token();

    $data['qbo_url'] = $qbo_auth_url;
    $data['qbo_connection'] = $allToken['qbo_connection'];

    $data['last_sync'] = $this->getLastSyncTime();

    $cmyObj = Session::get('company')['company_id'];
    $data['qbo_connect'] = Company::where('id', $cmyObj)->first();

    return view('company.qbo')->with($data);

    //$qbo_view = view('company.qbo',['qbo_connect' => $qbo_connect])->render();
    
    //$return = ['return_status'=>'redirect','qbo_view'=>$qbo_view];
    //print_r($qbo_view);exit();
    //return json_encode($return);

   }

   public function connectQBO()
   {
      \View::share('global_page_title', 'QuickBooks Online Integration');
     // Session::put('new_practices_flow', 0);

      Session::forget('new_practices_flow');
      //Session::forget('company')['qbo_connection'];

      $qbo_auth_url = $this->getOauth2Url(); 


      $data['qbo_url'] = $qbo_auth_url;
      return view('quickbooks.connect')->with($data);
      
   }

   public function getLastSyncTime()
   {
     $userObj = $this->getUserAndCompanyId();

     $check = Company::where('id', $userObj['company_id'])->first();

     if($check)
      return $check->last_sync;
     else
      return "";
   }

   public function checkReamIdforCompany($userObj, $realmid)
   {
     $result = false;


     $check = Company::where('id', '!=', $userObj['company_id'])
                       ->where('qbo_realmid', $realmid)->first();

     if($check)
        $result = true;

      $check1 = Company::where('id', $userObj['company_id'])->first();

      if($check1->qbo_realmid && $check1->qbo_realmid != $realmid)
        $result = true;

      return $result;
   }

   public function generateToken(Request $request)
   {
    
      $input = $request->all();

      if(isset($input['error'])){
        return view('quickbooks/qbo_error');
      }

      $state = $input['state'];   
      $code  = $input['code'];    
      $realmId = $input['realmId'];  

      $userObj = $this->getUserAndCompanyId();

      if(\Session::has('new_practices_flow')) { 
        $userObj = \Session::get('company_qbo');
      }

      $check_QBO = $this->checkReamIdforCompany($userObj, $realmId);
      if($check_QBO){

        $qboCallback = route('qbo_integration').'?error=true';
        //$request->session()->has('new_practices_flow')
        $successCallback = (Session::get('new_practices_flow')) ? route('company_qbo_integration_sync').'?error=true' : $qboCallback;

        return view('quickbooks/qbo_success', ['successCallback' => $successCallback]);
      } 

      // to-do : Check realmid for user

      $OAuth2LoginHelper  = $this->dataService->getOAuth2LoginHelper();
      $accessTokenObj     = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);
      
      $refreshToken       = $accessTokenObj->getRefreshToken();
      $accessToken        = $accessTokenObj->getAccessToken();

      // to-do : DB save

      $company = Company::where('id', $userObj['company_id'])->first();
      $company->qbo_realmid = $realmId;
      $company->qbo_access_token = $accessToken;
      $company->qbo_refresh_token = $refreshToken;
      $company->qbo_state = $state;
      $company->qbo_connection = 1;
      $company->last_sync = date('Y-m-d H:i:s');
      $company->save();

      $user_id = $company->user_id;
      $data = array(
        'active' =>1,
      );
      $user_id = User::where('id',$user_id)->update($data);

      $session_array = Session::get('company');
      $session_array['qbo_connection'] = 1;
      Session::put('company', $session_array);

      $this->dataService->updateOAuth2Token($accessTokenObj);
      
      $qboCallback     = route('qbo_integration_sync');
      //$request->session()->has('new_practices_flow')
      $successCallback = (Session::get('new_practices_flow')) ? route('company_qbo_integration_sync') : $qboCallback;

      return view('quickbooks/qbo_success', ['successCallback' => $successCallback]);

   }

   public function syncQBO()
   {
    \View::share('global_page_title', 'QuickBooks Online Integration');

     return view('quickbooks.qbo_sync');
   }

   public function registerSyncQBO()
   {
      $userObj = $this->getUserAndCompanyId();
      //Company::where('id', $userObj['company_id'])->update(['reg_steps' => 1]);

      $userId = Session::get('company_qbo')['user_id'];
      $companyId = Session::get('company_qbo')['company_id'];

      $company = Company::where('id', $companyId)->first();

      Session::put('company', [ 'company_id' => $companyId , 'global_company_name' => $company->name, 'qbo_connection' => 1]);
      

      User::where('id', $userId)->update(['active' => 1,'is_email_verified' => 1]);

      $clientData = User::where('id', $userId)->first();
      $cpaData = User::where('id', $clientData->parent_id)->first();
      return view('quickbooks.register_qbo_sync', ['cpaData' => $cpaData, 'clientData' => $clientData, 'companyId' => $companyId]);
   }

   public function disconnectQBO()
   {
     $userObj = $this->getUserAndCompanyId();

     if($userObj['company_id'] != ""){
          $company = Company::where('id', $userObj['company_id'])->first();
          $company->qbo_connection = 0;
          $company->save();

          $session_array = Session::get('company');
          $session_array['qbo_connection'] = 0;
          Session::put('company', $session_array);
      }

      return redirect()->back();

   }

   public function updateQBOTokens($newAccTok, $newRefTok, $userObj)
   {
       
      if($userObj['company_id'] != ""){
          Company::where('id', $userObj['company_id'])->update(['qbo_access_token' => $newAccTok, 'qbo_refresh_token' => $newRefTok]);
      }

      return false;
  }

    public function buildNewDataServiceBasedOnRealmid($realmId)
    {
       $comp = Company::where('qbo_realmid', $realmId)->first();

       if($comp){
            return $this->buildNewDataService($comp->id);
       }
    }

    public function buildNewDataService($comp_id = "")
    {
       
        $userObj = $this->getUserAndCompanyId($comp_id);
        $allToken = $this->get_all_token($comp_id);

        $accessToken = $allToken['qbo_access_token'];
        $refreshToken = $allToken['qbo_refresh_token'];
        $realmId = $allToken['qbo_realmid'];
        $error = false;
        $u_id = Auth::user()->id;

        $dataService = DataService::Configure(array(
             'auth_mode'       => 'oauth2',
             'ClientID'        => \Config::get('quickbooks.client_id'),
             'ClientSecret'    => \Config::get('quickbooks.client_secret'),
             'accessTokenKey'  => $accessToken,
             'refreshTokenKey' => $refreshToken,
             'QBORealmID'      => $realmId,
             'baseUrl'         => \Config::get('quickbooks.base_url'),
        ));

        $file = public_path('qboerrorlog.txt');
        $myfile = fopen($file, "a");

        try {
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $accessToken = $OAuth2LoginHelper->refreshToken();

            $newAccTok = $accessToken->getAccessToken(); 
            $newRefTok = $accessToken->getRefreshToken();

            $this->updateQBOTokens($newAccTok, $newRefTok, $userObj);

            $text = "\n"."From function .... Time : ".date('m/d/y H:i:s').", User id : ".$u_id.", Old R : ".$refreshToken.", New R : ".$newRefTok."\n";

            fwrite($myfile,  print_r($text, TRUE));


            $TokError = $OAuth2LoginHelper->getLastError();

            if($TokError){
                $accessToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
                 
                $newAccTok = $accessToken->getRefreshToken(); 
                $newRefTok = $accessToken->getRefreshToken(); 

                $this->updateQBOTokens($newAccTok, $newRefTok, $userObj);
            }
        }
        catch (\Exception $error) {
            if(Auth::check()){
                
            }
           $error = true;  
        }
        
        $errorqbo = $OAuth2LoginHelper->getLastError();

        if ($errorqbo || $accessToken == "") {
            $error = true;
        }

        if($error){
            return false;
            //if($error_opt == "")
             throw new \Exception('Please try after some times');
           // else
               // return false;
        }

        $dataService->updateOAuth2Token($accessToken);

        return $dataService;
    }

    public function updateLastSync($company_id)
    {
      $update = Company::where('id', $company_id)->update(['last_sync' => date('Y-m-d H:i:s')]);

      return true;
    }

    public function updateLastSyncReport($company_id)
    {
     
      $dt = new \DateTime();
      $dt->setTimezone(new \DateTimeZone('PST'));
      $pst = $dt->format('c');

      $update = Company::where('id', $company_id)->update(['qbo_time' => $pst]);

      return true;
    }

    public function importTest()
    {
      
      $dataService = $this->buildNewDataService();
      $userObj = $this->getUserAndCompanyId();

      $company =Company::where('id', $userObj['company_id'])->first();

      $accId = []; $all_acc = [];

      if(isset($company->qbo_time) && $company->qbo_time != ""){
        $entities = $dataService->Query("select * from Account where MetaData.LastUpdatedTime >= '".$company->qbo_time."'");
        if($entities){
          foreach ($entities as $key => $value) {
            $accId[] = $value->Id;
          }

          /********** if(count($accId) > 0){   // Will be useful for later by muruga
            $accounts = Accounts::where('company_id', $userObj['company_id'])->whereIn('qbo_id', $accId)->get();
            foreach ($accounts as $key => $value) {
              $all_acc['acc_'.$value->qbo_id] = $value;
            }
          } ***********/
        }
      }

      $accId = implode(',', $accId);

      if($accId == "" )
        return ['error' => false, 'msg' => 'no account to sync'];

      $i = 0;

      while ($i < 2) {

           if($i == 0){
                $start = date('Y-m-d', strtotime('first day of January'));  
                $end   = date('Y-m-d');
                $year  = date('Y');
           } else {
                $start = date('Y-m-d', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('first day of January')) ))); 
                $end   = date('Y-m-d', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('last day of December')) ))); 
                $year = date('Y', strtotime('-'.$i.' year', strtotime( date('Y-m-d', strtotime('first day of January')) ))); ;
           }

           $resArray['start'] = $start;
           $resArray['end']   = $end;
           $resArray['year']  = $year;

           $helper = new ReportsController();
           $helper->fetchQBOReports($start, $end, $year, $accId);

           $i++;
      }

      return ['error' => false, 'msg' => $accId.' has been fetched at '.$company->qbo_time];
    }

   public function importAccounts()
   {
        $result['error'] = true;
        $result['msg'] = 'failed to get accounts';
       
        $accountData = [];
       
        $userObj = $this->getUserAndCompanyId();
        if(\Session::has('new_practices_flow')) { 
          $userObj = \Session::get('company_qbo');
        }

        $this->updateLastSync($userObj['company_id']);

        $dataService = $this->buildNewDataService();
      
        $text = "\n"."Time : ".date('m/d/y H:i:s').", company_id : ".$userObj['company_id'].", data service: ";

        $file = public_path('qboerrorlog.txt');
        $myfile = fopen($file, "a");
        fwrite($myfile,  print_r($text, TRUE));
        fwrite($myfile,  print_r($dataService, TRUE));

        if (!$dataService) {
            $result['error'] = true;
            $result['msg'] = 'Failed to Fetch Accounts';
            return $result;
        }
        
        $qboCompanyName = $dataService->FindAll('Company');
        if(!empty($qboCompanyName)) {
          $data = array(
            'qbo_company_name' =>$qboCompanyName[0]->CompanyName,
          );
          Company::where('id', $userObj['company_id'])->update($data);
        }

        $allAccount = $dataService->FindAll('Account');

        if(empty($allAccount)){
          $result['error'] = true;
          $result['msg'] = 'No Accounts Found';
          return $result;
        }

        $accountService = new AccountsController();
        $accountData = $accountService->doImport($userObj['company_id'], $allAccount);
        $accountService->save($accountData);

        $error = $dataService->getLastError();
        if ($error) {
          $body = $error->getResponseBody(); 
          $msg = $error->parseResponse($body); 
          $detail = $error->getIntuitErrorDetail();

          $this->writeErrorLogQB($detail);
          $result['error'] = true;
          $result['msg'] = 'Error Occured !';
          return $result;
        }

        $result['accounts'] =  $accountData;
        $result['error'] = false;
        $result['msg'] = 'Accounts Fetched Successfully';
        return $result;
        
        // to-do : Here below code need to implement.. later
        /*$j = 0;
        while(1) { 
        $allAccount = $dataService->FindAll('Account', $j, 500);
        if(sizeof($allAccount) <= 0){
          break;
        }
        else {
          $accountService = new ImportAccount();
          $accountData = $accountService->doImport($company_id, $allAccount);
          $accountService->save($accountData);
        }
        $j += 500; 
        }*/
   }

   public function importCronAccounts($company_id, $user_id)
   {
        $error['error'] = true;
        $error['msg'] = 'failed to get accounts';
       
        $accountData = [];
       
        //$userObj = $this->getUserAndCompanyId();
        /*if(\Session::has('new_practices_flow')) { 
          $userObj = \Session::get('company_qbo');
        }*/

        //echo "hello";print_r($userObj);exit();
        $this->updateLastSync($company_id);


        $userObj = $this->getUserAndCompanyId($company_id);
        

        $allToken = $this->get_all_token($company_id);

        $accessToken = $allToken['qbo_access_token'];
        $refreshToken = $allToken['qbo_refresh_token'];
        $realmId = $allToken['qbo_realmid'];
        $error = false;

        $dataService = DataService::Configure(array(
             'auth_mode'       => 'oauth2',
            'ClientID'        => \Config::get('quickbooks.client_id'),
              'ClientSecret'    => \Config::get('quickbooks.client_secret'),
             'accessTokenKey'  => $accessToken,
             'refreshTokenKey' => $refreshToken,
             'QBORealmID'      => $realmId,
             'baseUrl'         => \Config::get('quickbooks.base_url'),
        ));



        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessToken = $OAuth2LoginHelper->refreshToken();



        $newAccTok = $accessToken->getAccessToken(); 
        $newRefTok = $accessToken->getRefreshToken();

        $this->updateQBOTokens($newAccTok, $newRefTok, $userObj);

        /*
          Log Files
          By: Rajan 
          On: 08-07-2019
        */

        $text = "\n"."Time : ".date('m/d/y H:i:s').", User id : ".$u_id.", Old R : ".$refreshToken.", New R : ".$newRefTok."\n";

        $file = public_path('qboerrorlog.txt');
        $myfile = fopen($file, "a");
        fwrite($myfile,  print_r($text, TRUE));

        $TokError = $OAuth2LoginHelper->getLastError();

        if($TokError){
            $accessToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
             
            $newAccTok = $accessToken->getRefreshToken(); 
            $newRefTok = $accessToken->getRefreshToken(); 

            $this->updateQBOTokens($newAccTok, $newRefTok, $userObj);
        }


        $allAccount = $dataService->FindAll('Account');


        $accountService = new AccountsController();
        $accountData = $accountService->doImport($userObj['company_id'], $allAccount);
        //print_r($accountData);exit();

        $accountService->save($accountData);

        return [
            'accounts' =>  $accountData
        ];
        
   }


   public function getCompanyBasedOnRealmid($realmId)
   {
        $user = Company::where('qbo_realmid', $realmId)->first();

       if($user){
            return $user;
       }
    }

   public function performWebhooks(Request $request)
   {
        $error['error'] = false;
        $error['msg'] = 'Account created successfully';

        $data = $request->all();

        $realmId = $data['eventNotifications'][0]['realmId'];
        $allEntities = $data['eventNotifications'][0]['dataChangeEvent']['entities'];

        $text = "\n"."Received a Webhooks Notification for Realmid ".$realmId."\n";

        $file = public_path('webhooks.txt');
        $myfile = fopen($file, "a");

        fwrite($myfile,  print_r($text, TRUE));
        fwrite($myfile,  print_r($data, TRUE));

        $comObj = $this->getCompanyBasedOnRealmid($realmId);

        if(!isset($comObj) || empty($comObj)){
              $error['error'] = true;
              $error['msg'] = 'Company not found';
             return $error;
        }

        $dataService = $this->buildNewDataServiceBasedOnRealmid($realmId);

        $text = "\n"."Received a Webhooks Notification for company ".$comObj->id." and Realmid ".$realmId."\n";

         foreach ($allEntities as $key => $entities) {
          $name = $entities['name'];
          $operation = $entities['operation'];
          $id = $entities['id'];
          $last_update = $entities['lastUpdated'];
          $text .= "Processing ".$operation." Operation for ".$name."\n";

          if($name == 'Account'){
            $res = $this->importWebHookAccount($dataService, $comObj, $id);
          }

        }

        $text .= "\n".'Process end at '.date('m/d/y H:i:s');
        fwrite($myfile,  print_r($text, TRUE));

        return $error;
   }

    public function importWebHookAccount($dataService, $comObj, $id)
    {
        
      $entities = $dataService->Query("select * from Account where id='".$id."'");
      $accountService = new AccountsController();
      $accountData = $accountService->doImport($comObj->id, $entities);
      $savedAcc = $accountService->save($accountData); 
        
        return [
                'success' => true,
                'Accounts' =>  $savedAcc
            ];
    }


    public function importCronReports($startDate, $endDate, $company_id, $method = 'Accrual', $accId = "", $type = "g")
   {
        $result['error'] = true;
        $result['msg'] = 'failed to get accounts';
       
        $accountData = [];
       
        /*$userObj = $this->getUserAndCompanyId();
        if(\Session::has('new_practices_flow')) { 
          $userObj = \Session::get('company_qbo');
        }*/

        $this->updateLastSyncReport($company_id);

        try {

              $dataService = $this->buildNewDataService($company_id);

              if($dataService)
              {
                  $serviceContext = $dataService->getServiceContext();
                  
                  // Prep Data Services
                  $reportService = new ReportService($serviceContext);

                  if (!$reportService) {
                      $result['msg'] = 'Problem while initializing ReportService.';
                      return $result;
                  }
                  // Y-m-d format
                  $reportService->setStartDate($startDate);
                  $reportService->setEndDate($endDate);
                  $reportService->setAccountingMethod($method);

                  if($accId != "")
                    $reportService->setAccount($accId);

                    $reportService->setSummarizeColumnBy("Days");

                    $GeneralAccReport = $reportService->executeReport(ReportName::PROFITANDLOSS);

                    //print_r($reportService);die('hello')
                    //$error = $dataService->getLastError();

                    /*if ($error) {
                      $body = $error->getResponseBody(); 
                      $msg = $error->parseResponse($body); 
                      $detail = $error->getIntuitErrorDetail();

                    }*/
                    //print_r($msg);print_r($detail);
                    //print_r($GeneralAccReport);die('hello');




                  /*if($type == 'g'){
                     $reportService->setGroupBy("Month");
                     $reportService->setColumns("tx_date,txn_type,doc_num,name,memo,split_acc,subt_nat_amount,rbal_nat_amount,account_name");

                    $GeneralAccReport = $reportService->executeReport(ReportName::GENERALLEDGER);

                  } elseif($type == 'bs') {
                    $reportService->setSummarizeColumnBy("Month");

                    $GeneralAccReport = $reportService->executeReport(ReportName::BALANCESHEET);
                  } else {
                    $reportService->setSummarizeColumnBy("Month");

                    $GeneralAccReport = $reportService->executeReport(ReportName::PROFITANDLOSS);
                  }*/

                  if (!$GeneralAccReport) {
                      $result['msg'] = "GeneralAccReport Is Null.\n";
                  } else {
                      $result['error'] = false;
                      $result['data'] = $GeneralAccReport;
                  }
                } else {
                  $result['msg'] = 'Problem while initializing DataService.';
                }

        } catch (\Exception $error) {
          return $error->getMessage();
        }
        return $result;
   }


   public function importReports($startDate, $endDate, $method = 'Accrual', $accId = "", $type = "g")
   {
        $result['error'] = true;
        $result['msg'] = 'failed to get accounts';

        $accountData = [];
       
        $userObj = $this->getUserAndCompanyId();
        if(\Session::has('new_practices_flow')) { 
          $userObj = \Session::get('company_qbo');
        }
        $this->updateLastSyncReport($userObj['company_id']);

        try {

              $dataService = $this->buildNewDataService();

              if($dataService)
              {

                  $serviceContext = $dataService->getServiceContext();
                  
                  // Prep Data Services
                  $reportService = new ReportService($serviceContext);

                  if (!$reportService) {
                      $result['msg'] = 'Problem while initializing ReportService.';
                      return $result;
                  }

                  // Y-m-d format
                  $reportService->setStartDate($startDate);
                  $reportService->setEndDate($endDate);
                  $reportService->setAccountingMethod($method);

                  if($accId != "")
                    $reportService->setAccount($accId);

                    $reportService->setSummarizeColumnBy("Days");

                    $GeneralAccReport = $reportService->executeReport(ReportName::PROFITANDLOSS);

                  //print_r($reportService);die('hello')
                  //$error = $dataService->getLastError();

                  /*if ($error) {
                    $body = $error->getResponseBody(); 
                    $msg = $error->parseResponse($body); 
                    $detail = $error->getIntuitErrorDetail();

                  }*/
                  //print_r($msg);print_r($detail);
                  //print_r($GeneralAccReport);die('hello');

                  if (!$GeneralAccReport) {
                      $result['msg'] = "GeneralAccReport Is Null.\n";
                  } else {
                      $result['error'] = false;
                      $result['data'] = $GeneralAccReport;
                  }
                } else {
                  $result['msg'] = 'Problem while initializing DataService.';
                }

        } catch (\Exception $error) {
          return $error->getMessage();
        }
        return $result;
   }
   
   public function skipQbo(Request $request)
   {
    $user_id = $request->input('user_id_qbo');
    $type    = $request->input('type');
    if($user_id != ""){
      $user           = User::where('id',$user_id)->get();
      $parent_id      = $user[0]->parent_id;
      $parent_email   = User::where('id',$parent_id)->get();
      $email          = $parent_email[0]->email;
      $data['name']   = $user[0]->first_name;
      $send = Mail::send('mail_templates.admin_confirmation',$data,function($message) use ($email){
            $message->to($email, 'Practice gauge')->subject('Practice gauge');
     }); 
      return 1;

    }else{
       return 0;
    }
   }

    public function skipQboStd(Request $request)
    {
      $user_id = $request->input('user_id_qbo');
      $type    = $request->input('type');
      if($user_id != ""){
        $user           = User::where('id',$user_id)->get();
        //$parent_id      = $user[0]->parent_id;
        $parent_email   = User::where('role_id',1)->get();
        $email          =$parent_email[0]->email;
        $data['name']   = $user[0]->first_name;
        $send = Mail::send('mail_templates.admin_confirmation',$data,function($message) use ($email){
              $message->to($email, 'Practice gauge')->subject('Practice gauge');
       }); 
        return 1;

      }else{
         return 0;
      }
   }
}
