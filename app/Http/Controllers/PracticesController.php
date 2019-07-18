<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Session;
use Auth;
use App\Models\UserDetails;
use App\Models\Request_email;
use App\Models\User;
use App\Models\Company;
use App\Models\Entity;
use App\Models\Practices;
use App\Models\States;
use App\Models\UserCompanyMap;

class PracticesController extends Controller
{
    public function availablePractices()
    { 
      \View::share('global_page_title', 'Practices');
      \View::share('global_menu', 48);
      $company_id = Session::get('company')['company_id'];
      $user_id = Auth::user()->id;
      $role_id = Auth::User()->role_id;
      
      if($role_id == 4) { //Firm Admin
        $fetchData = User::where('id', $user_id)->select('parent_id')->first();
        $user_id = $fetchData->parent_id;
      }
      $practicesList = User::join('user_company_mapping', 'user_company_mapping.user_id', '=', 'users.id')->join('company', 'company.id', '=', 'user_company_mapping.company_id')->leftjoin('user_details', 'user_details.user_id', '=', 'users.id')->where('user_company_mapping.company_id', '=', $company_id)->where('users.role_id', '=', 5)->where('users.user_page', '=', 0)->where('company.deleted', '=', 0)->where('user_company_mapping.deleted', '=', 0)->select('*', 'company.id as company_id')->get();
        

      $practicesCount = \DB::select(\DB::raw("SELECT practices_count from users where id = (SELECT users.parent_id FROM company inner join users on users.id = company.user_id WHERE company.id = $company_id)"));     

        //$practicesList = UserCompanyMap::join('company', 'company.id', '=', 'user_company_mapping.company_id')->join('users', 'users.id', '=', 'user_company_mapping.user_id')->select('company.*', 'user_company_mapping.*','users.active')->where('user_company_mapping.user_id', $user_id)->get();

        $entity_type = Entity::get()->toArray();
        $practices_type = Practices::get()->toArray();
        
        return view('practices.view_practices', ['practicesList' => $practicesList, 'practicesCount' => $practicesCount, 'entity_type' => $entity_type, 'practices_type' => $practices_type, 'role_id' => $role_id]);
    }


    public function selfInvite()
    {
        //print_r($_GET['confirmation_code']);die;
        $id = isset($_GET['confirmation_code'])?(base64_decode($_GET['confirmation_code'])):'';
        $company_id = isset($_GET['practices'])?(base64_decode($_GET['practices'])):'';
        //print_r();die;

        Session::put('company_qbo', [ 'company_id' => $company_id, 'user_id' => $id]);

        $getDetails = User::where('id',$id)->get()->toArray();       
        $getCompany = Company::where('id',$company_id)->get()->toArray();   
        $qboHelper = new QuickbooksController();
        $data['qbo_auth_url'] = $qboHelper->getOauth2Url();
        $userDetails = UserDetails::where('user_id',$id)->get()->toArray();
        //print_r($userDetails);die;
        $data['states'] = States::get()->toArray();
        $data['userDetails'] = UserDetails::get()->toArray();
        $data['name'] = isset($getDetails[0]['first_name'])?$getDetails[0]['first_name']:'';
        $data['email'] = isset($getDetails[0]['email'])?$getDetails[0]['email']:'';
        $data['practices_name'] = isset($getCompany[0]['name'])?$getCompany[0]['name']:'';
        $data['steps'] = isset($getCompany[0]['steps'])?$getCompany[0]['steps']:'';
        $data['entity_type'] = Entity::get()->toArray();
        $data['practices_type'] = Practices::get()->toArray();
        $data['userDetails'] = $userDetails;
        Session::put('new_practices_flow', TRUE);
        return view('practices.self_invite')->with($data);
    }

    public function selfInviteStd()
    {
        $id = isset($_GET['confirmation_code'])?(base64_decode($_GET['confirmation_code'])):'';
        //$company_id = isset($_GET['practices'])?(base64_decode($_GET['practices'])):'';
        //print_r();die;
        Session::put('company_qbo', [ 'user_id' => $id]);
        //print_r(Session::get('company_qbo'));exit();

        $getDetails = User::where('id',$id)->get()->toArray();       
       // $getCompany = Company::where('id',$company_id)->get()->toArray();   
        $qboHelper = new QuickbooksController();
        $data['qbo_auth_url'] = $qboHelper->getOauth2Url();
        $userDetails = UserDetails::where('user_id',$id)->get()->toArray();
        //print_r($userDetails);die;
        $data['states'] = States::get()->toArray();
        $data['userDetails'] = UserDetails::get()->toArray();
        $data['name'] = isset($getDetails[0]['first_name'])?$getDetails[0]['first_name']:'';
        $data['email'] = isset($getDetails[0]['email'])?$getDetails[0]['email']:'';
        $data['practices_name'] = isset($getCompany[0]['name'])?$getCompany[0]['name']:'';
        $data['steps'] = isset($getCompany[0]['steps'])?$getCompany[0]['steps']:'';
        $data['entity_type'] = Entity::get()->toArray();
        $data['practices_type'] = Practices::get()->toArray();
        $data['userDetails'] = $userDetails;
        Session::put('new_practices_flow', TRUE);
        return view('practices.self_invite_std')->with($data);
    }
    
    public function requestEmailSend(Request $request){      
        $user_id = Auth::user()->id;
        $email = $request->input('email');

        $welcome_sep = $request->input('welcome_msg_sep');
        $req_practice = $request->input('req_practice');
        $req_send    =$request->input('req_send');
        $prag      = $request->input('prag');
        $dName     = $request->input('dName');
        $cName     = $request->input('cName');
        $company = $request->input('company');
        $confirmation_code = str_random(30);
        $parentID = Auth::user()->id;

        $mfaData = User::where('id', $user_id)->select('company_name')->first();
        $check_user_email = User::where('email',$email)->first();

        if(!$check_user_email) {
          $userSave = User::create([
            'first_name'        => $request->input('first_name'),
            'last_name'         => $request->input('last_name'),
            'confirmation_code' => $confirmation_code,
            'email'             => $email,
            'parent_id'         => $parentID,
            'role_id'           => '5',
            'active'            => '0',
            'is_subscription'   => 1,
            'company_name'      => $mfaData->company_name,
          ]);
        } 
        else {
          $userSave = $check_user_email;
        }
      
       $company = Company::create([
        'user_id' => $userSave->id,
        'name'    => $request->input('practices_name'),
        'step'    => 1,
       ]);
        $requestSend = Request_email::create([
         'user_id' => $userSave->id,     
         'first_name' => $request->input('first_name'),
         'last_name' => $request->input('txtPName'),
         'email'      => $email,
         'practices_name' => $request->input('practices_name'),  
         'message' => $request->input('welcome_msg'),
         'deleted'  => 0,
       ]);

        $cmyMap = new UserCompanyMap;
        $cmyMap->user_id = $user_id;
        $cmyMap->company_id =  $company->id;
        $cmyMap->save();

        $cmyMapO = new UserCompanyMap;
        $cmyMapO->user_id = $userSave->id;
        $cmyMapO->company_id =  $company->id;
        $cmyMapO->save();


       $id = $userSave->id;
       $company_id = $company->id;
       $id_url = base64_encode($id);
       $company_url = base64_encode($company_id);
       $arrayCompany = array('steps'=>'1');
       $upDate = Company::where('user_id',$id)->update($arrayCompany);
       if($id > 0){
           $data  = array(                   
            //'request_email' => $request->input('welcome_msg'), 
            'id'                    => base64_encode($id),
            'company_id'            => $company_url,
            'name'                  => $request->input('first_name'),
            'welcome_sep'           => $welcome_sep,
            'req_practice'          => $req_practice,
            'prag'                  => $prag,
            'req_send'              => $req_send,
            'dName'                 => $dName,
            'cName'                 => $cName,
           
       );
           
           $result['msg'] = 'Mail Sended successfully';
           Mail::send('mail_templates.email_request',$data,function($message) use ($email){
            $message->to($email, 'Practice gauge')->subject('Practice Gauge Invitation');
        });    


       }
    }

    public function emailSendCpa(Request $request) 
    {
      Session::forget('new_practices_flow');
      Session::forget('company')['qbo_connection'];
      $email = $request->input('email');

      //$id = Session::get('company')['user_id'];
      //$company_id = Session::get('company')['company_id'];

      $data = array(
        'name' => $request->input('name'),
        'client_name' => $request->input('client_name'),
        'welcome_sep' => 'Practice Gauge',
        'req_practice' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut eleifend dolor. Nulla ac tristique ligula, non interdum elit.',
        'prag' => 'Please us the following link to configure your practice (bosh) on Practice Gauge!

',
      );
      
      Mail::send('mail_templates.practice_confirmation', $data, function($message) use ($email){
        $message->to($email, 'Practice Gauge')->subject('New Practice confirmation ');
      });
    }

    function emailSendAdmin()
    {
      Session::forget('new_practices_flow');
      Session::forget('company')['qbo_connection'];

      $data = array(
        'welcome_sep' => 'Practice Gauge',
      );
      $user   = User::where('role_id',1)->first();
      $email  = $user->email;
      Mail::send('mail_templates.admin_confirmation', $data, function($message) use ($email){
        $message->to($email, 'Practice Gauge')->subject('Admin confirmation ');
      });
    }
    

    public function selfInviteNew(Request $request)
    {

       $user_id = Auth::user()->id;
       
        $role_id = $request->input('roleID');
        $parentID = Auth::user()->id;
        $confirmation_code = str_random(30);
        $email = $request->input('email');
        $fName = $request->input('first_name');
        $lName = $request->input('last_name');
        $practices_name = $request->input('practices_name');

        $mfaData = User::where('id', $user_id)->select('company_name')->first();
        $check_user_email = User::where('email',$email)->first();

        if(!$check_user_email) {
          $createUser = User::create([
            'first_name'        => $fName,
            'last_name'         => $lName,
            'email'             => $email,
            'role_id'           => 5,
            'parent_id'         => $parentID,
            'active'            => 0,
            'confirmation_code' => $confirmation_code,
            'is_subscription'   => 1,
            'company_name'      => $mfaData->company_name,
          ]);
        } 
        else {
          $createUser = $check_user_email;
        }

        $companyCreate = Company::create([
          'name'    => $practices_name, 
          'user_id' => $createUser->id,
          'steps'   => 1,
        ]);
        $data['user_id'] = $createUser->id;
        $data['company_id'] = $companyCreate->id;

        $cmyMap = new UserCompanyMap;
        $cmyMap->user_id = $user_id;
        $cmyMap->company_id =  $companyCreate->id;
        $cmyMap->save();

        $cmyMapO = new UserCompanyMap;
        $cmyMapO->user_id = $createUser->id;
        $cmyMapO->company_id =  $companyCreate->id;
        $cmyMapO->save();


        return $data;

    }

    public function searchPractices(Request $request)
    {
        $search = $request->search;
        $user_id = Auth::user()->id;
        $practicesList = \DB::select(\DB::raw("select `company`.* from `company` inner join `user_company_mapping` on `user_company_mapping`.`company_id` = `company`.`id` where `user_company_mapping`.`user_id` = $user_id and company.deleted = 0 and user_company_mapping.deleted = 0 and `name` like '%$search%'"));     
        return view('practices.search',['practicesList' => $practicesList]);
        
    }
    
    public function sendLoginPwd(Request $request){
      $parentName = Auth::user()->first_name;
      $id = $request->input('id');
      $practicesNew = User::where('id',$id)->get()->toArray();
      $pwd = $this->rand_string(8);
      $email = $practicesNew[0]['email'];
      $first_name = $practicesNew[0]['first_name'];
      $arrayData = array('password'=>Hash::make($pwd),'pwd'=>$pwd);
      $updatePwd = User::where('id',$id)->update($arrayData);
      if($updatePwd > 0){
        $data  = array('email' => $email,'pwd'=> $pwd,'name'=>$parentName);
        $result['msg'] = 'Mail Sended successfully';
        Mail::send('mail_templates.newSendLogin',$data,function($message) use ($email){
          $message->to($email, 'Practice gauge')->subject('Practice Gauge credentials');
         }); 
         return $updatePwd;  
       }else{
          $result['msg'] = 'Mail Sended Failed';
          return $updatePwd;
       }


   }
  
  public function rand_string($length) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*";
    return substr(str_shuffle($chars),0,$length);
  }

  public function qboSkip(){
    return view('/practices/skip_success');
  }

  public function basicInformationSvd(Request $request)
  {
    $createUserDetails = UserDetails::firstOrNew(array('user_id'=> $request->input('user_id')));
    $createUserDetails->user_id             = $request->input('user_id');
    $createUserDetails->address             = $request->input('address');
    $createUserDetails->state               = $request->input('state');
    $createUserDetails->city                = $request->input('city');
    $createUserDetails->pincode             = $request->input('zipcode');
    $createUserDetails->contact_person_name = $request->input('cName');
    $createUserDetails->phone               = $request->input('cPhone');
    $createUserDetails->prefer_communication= $request->input('rdgPreferred');
    $createUserDetails->save();

    Company::where('user_id',$request->user_id)->where('id', $request->company_id)->update(['name'=> $request->input('pName')]);

    if($createUserDetails->id > 0){
      $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 2]);
    }else{
      $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 1]);
    }
    return $createUserDetails->id;
  }

  //stduser saved
  public function basicInformationSvdStd(Request $request)
  {
    $company_svd               = Company::firstOrNew(array('user_id'=> $request->input('user_id')));
    $company_svd->user_id      = $request->input('user_id');
    $company_svd->name         = $request->input('pName');
    $company_svd->save();

    /*
    2019-06-21
    Add records to user company mapping table
    */ 
    $cmyMap = new UserCompanyMap;
    $cmyMap->user_id = $request->input('user_id');
    $cmyMap->company_id =  $company_svd->id;
    $cmyMap->save();
    //UserCompanyMap::firstOrNew(array('user_id'=> $request->input('user_id') , 'company_id' => $company_svd->id));

    \Session::put('company_qbo', [ 'company_id' => $company_svd->id, 'user_id' => $company_svd->user_id]);
    //print_r(\Session::get('company_qbo'));exit();
    $createUserDetails = UserDetails::firstOrNew(array('user_id'=> $request->input('user_id')));
    $createUserDetails->user_id             = $request->input('user_id');
    $createUserDetails->address             = $request->input('address');
    $createUserDetails->state               = $request->input('state');
    $createUserDetails->city                = $request->input('city');
    $createUserDetails->pincode             = $request->input('zipcode');
    $createUserDetails->contact_person_name = $request->input('cName');
    $createUserDetails->phone               = $request->input('cPhone');
    $createUserDetails->prefer_communication= $request->input('rdgPreferred');
    $createUserDetails->save();

    if($createUserDetails->id > 0){
    $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 2]);
    }else{
    $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 1]);
    }

    return $createUserDetails->id;
  }

  public function practiceSpecificsSvd(Request $request)
  {
    //print_r($request->input());die;
    $createUserDetails = UserDetails::firstOrNew(array('user_id'=> $request->input('user_id')));
    $createUserDetails->user_id                          = $request->input('user_id');
    $createUserDetails->start_year                       = $request->input('year');
    $createUserDetails->operatories                      = $request->input('operatories_count');
    $createUserDetails->entity_type                      = $request->input('entity_count');
    $createUserDetails->types_of_practice                = $request->input('practices_count');
    $createUserDetails->total_owner                      = $request->input('owners_count');
    $createUserDetails->total_employee                   = $request->input('employee_count');
    $createUserDetails->total_fte                        = $request->input('fte_count');
    $createUserDetails->is_milling_unit                  = $request->input('rdgMill');
    $createUserDetails->implants                         = $request->input('rdgImplants');
    $createUserDetails->has_aligner_services             = $request->input('rdgAligner');
    $createUserDetails->save();

    Company::where('user_id',$request->user_id)->update(['types_of_practice' => $request->input('practices_count')]);

    if($createUserDetails->id > 0){
    $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 3]);
    }else{
    $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 2]);
    }

    return $createUserDetails->id;
  }

  public function practiceSpecificsSvdStd(Request $request)
  {
    $createUserDetails = UserDetails::firstOrNew(array('user_id'=> $request->input('user_id')));
    $createUserDetails->user_id                          = $request->input('user_id');
    $createUserDetails->start_year                       = $request->input('year');
    $createUserDetails->operatories                      = $request->input('operatories_count');
    $createUserDetails->entity_type                      = $request->input('entity_count');
    $createUserDetails->types_of_practice                = $request->input('practices_count');
    $createUserDetails->total_owner                      = $request->input('owners_count');
    $createUserDetails->total_employee                   = $request->input('employee_count');
    $createUserDetails->total_fte                        = $request->input('fte_count');
    $createUserDetails->is_milling_unit                  = $request->input('rdgMill');
    $createUserDetails->implants                         = $request->input('rdgImplants');
    $createUserDetails->has_aligner_services             = $request->input('rdgAligner');
    $createUserDetails->save();

    Company::where('user_id',$request->user_id)->update(['types_of_practice' => $request->input('practices_count')]);

    if($createUserDetails->id > 0){
    $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 3]);
    }else{
    $company_steps = Company::where('user_id',$request->user_id)->update(['steps'=> 2]);
    }

    return $createUserDetails->id;
  }

  public function editPracticeDetails(Request $request) 
  {
    $id = $request->id;
    $company_id = $request->company_id;
     
    $editDetails = Company::join('users', 'users.id', '=', 'company.user_id')->leftjoin('user_details', 'user_details.user_id', '=', 'users.id')->where('company.id', $company_id)->first();

    return $editDetails;   
  }

  public function switchLogin(Request $request) 
  {
    $company_id = $request->company_id;
    $company = Company::where('id', $company_id)->first();
  
    Session::put('company', [ 'company_id' => $company_id , 'global_company_name' => $company->name, 'qbo_connection' => 1]);

    return $company_id;   
  }

  public function confirmDelete(Request $request) 
  {
    $company_id = $request->company_id;
    Company::where('id', $company_id)->update([ 'deleted' => 1]);
    UserCompanyMap::where('id', $company_id)->update([ 'deleted' => 1]);
    return $company_id;
  }
}

