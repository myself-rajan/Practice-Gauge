<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mails;
use App\Models\KnownAbout;
use App\Models\Region;
use App\Models\States;
use App\Models\UserDetails;
use App\Models\RegionUserTable;
use App\Models\Company;
use App\Models\UserCompanyMap;
use Auth;

use Session;
use DB;

use App\Models\User;

class RegisterController extends Controller
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
    public function cpaReg()
    {
      $data['aboutKnown'] = KnownAbout::get()->toArray();
      $data['region']  =  Region::get()->toArray();
      $data['states']  =  States::get()->toArray();
      return view('/auth/signup_cpa',$data);
    }
    public  function signupStd()
    {
      return view('/auth/signup_std');
    }
  public function saveRegStd(Request $request){
    $email              = $request->input('email');
    $password           = Hash::make($request->input('password'));
    $pwd                = $request->input('password');
    $role_type          = $request->input('user_type');
    $organization_name  = $request->input('organization_name');
    $confirmation_code = str_random(30);
    $createStd = User::create([
      'email' => $email,
      'password' => $password,
      'pwd'      => $pwd,
      'role_id'  => $role_type,
      'confirmation_code' => $confirmation_code,
      'active'            => 0,
      'company_name' => $organization_name,
    ]);
    $id =$createStd->id;
    $UserDetails = UserDetails::create([
      'user_id' => $id,
      'steps'   => 1,
    ]);
    if($id > 0){
      $data  = array(                   
        'confirmation_code' => $confirmation_code,
        'id'                => base64_encode($id),         
      );
      $users = $email;
      $result['msg'] = 'Mail Sended successfully';
      //Mail::to($users)->send(new Mails($data))->subject('Practice gauge'); 
       Mail::send('mail_templates.mailnotify',$data,function($message) use ($email){
      $message->to($email, 'Practice gauge')->subject('Practice Gauge Registration');
    });               
    }
    return $id;

  }

  public function saveRegCpa(Request $request){
    $name = $request->input('first_name');
    $lname = $request->input('last_name');
    $email = $request->input('email');
    $practices_count = $request->input('practices_count');
    $orderAbout = $request->input('order_about');
    $orderAbout1 = $request->input('order_about1');
    $region      = $request->input('region');
    $statesData      = $request->input('states');
    $company_name      = $request->input('company_name');

    $states = '';
    if($statesData != '')
      $states = implode($statesData, ',');

    $role_type  = $request->input('role');
    $confirmation_code = str_random(30);
    $createCPA = User::create([ 
      'first_name' => $name,
      'last_name'  => $lname,
      'email'      => $email,
      'company_name' => $company_name,
      'practices_count' => $practices_count,
      'role_id' => $role_type,
      'confirmation_code' => $confirmation_code,
      'is_subscription' => 1,
      'active'    => 0,
      'deleted'   => 0,
    ]);

    $id = $createCPA->id;    
    if($id > 0){
      $known_about = UserDetails::create([
        'know_about'  => $orderAbout,
        'know_about1' => $orderAbout1,
        'user_id'     => $id,
        'region_id'     => $region[0],
        'state_ids'     => $states,
        //'steps'       => 1,
      ]);

      /*foreach($region as $value){
        RegionUserTable::create([
          'region_id' => $value,
          'user_id' => $id,
        ]);
      }*/

      $data  = array('confirmation_code' => $confirmation_code,'name'=> $name.' '.$lname);
      $result['msg'] = 'Mail Sended successfully';
      Mail::send('mail_templates.confirmationCPA',$data,function($message) use ($email){
        $message->to($email, 'Practice gauge')->subject('Practice Gauge Registration');
      });    
    }

    return $id;
}

public function checkEmailStd(Request $request){
  $email          =  $request->input('email');
  $check_user_email = User::where('email',$email)->get()->count();

  if( $check_user_email  > 0)
    return $check_user_email;
  else
    return $check_user_email;
}

public function checkEmailExistStd(Request $request){
  $email          =  $request->input('email');
  $check_user_email = User::where('email',$email)->first();/*->count()*/

  return $check_user_email;

  /*if( $check_user_email  > 0)
    return $check_user_email;
  else
    return $check_user_email;*/
}

public function checkPracticeNameExist(Request $request){
  $practiceName  =  $request->input('practiceName');
  $checkPracticeName = Company::where('name', $practiceName)->where('deleted', 0)->first();/*->count()*/

  return $checkPracticeName;

  /*if( $check_user_email  > 0)
    return $check_user_email;
  else
    return $check_user_email;*/
}

public function sentStdSuccess(Request $request){

  return view('/auth/email_send_std');
}

public function registerVerify(Request $request){
  $data = $request->all();
  if(!$data)
  {
    $data = array(
     'msg' => 'Invalid activation link.',
   );
    return view('/mail/confirmation_failure');
  }
  $user = User::where('confirmation_code', $data['confirmation_code'])->first();
  if($user->is_email_verified == ""){
    if ( !$user)
    {
      return view('/mail/confirmation_failure');
    }
    $user->is_email_verified = 1;
    $user->active = 1;
    $user->save();
    $data['verified_email'] = '1';
    //return redirect()->route('stdVerifySuccess');
    return view('/auth/stdVerifySuccess');
  }else{
    //return redirect()->route('stdVerifySuccess');
    return view('/auth/stdVerifySuccess');
  }
}

// public function stdVerifySuccess(Request $request){
//     return view('/auth/stdVerifySuccess');

// }

public function registerVerifyCPA(Request $request){
  $data = $request->all();
  if(!$data)
  {
    $data = array(
     'msg' => 'Invalid activation link.',
   );
    return view('/mail/confirmation_failure');
  }
  // $data['name'] = $user['first_name'].' '.$user['last_name'];
  $user = User::where('confirmation_code', $data['confirmation_code'])->first();
  if($user->is_email_verified == ""){
    $data['id'] = $user['id']; 
    if ( !$user)
    {
      return view('/mail/confirmation_failure');
    }
    $user->is_email_verified = 0;
    $user->active = 0;
    $user->save();
    $data['verified_email'] = '1';
    return view('/auth/new_password',$data);
    return redirect()->route('/new_password')->with('success','Email verifiyed successfully!');
  }else{
    return redirect()->route('login')->with('success','Email already verifiyed!');
  }

}

public function saveNewPass(Request $request){
 $array = array(
  'password' => Hash::make($request->input('password')),
  'pwd'      => $request->input('password'),
  'status' => 1,
  'is_email_verified' => 1,
  'active' => 1,
);
 $id = $request->input('user_id');
 $update = User::where('id',$id)->where('status',Null)->update($array);
 return $update;
}
public function faliure(){
  return view('/auth/confirmation_failure');
}
public function forget_pass(){
  return view('/auth/passwords/forget_password');
}
public function send_request_pwd(Request $request){
  $email = $request->input('email');
  $check_user_id = User::where('email',$email)->get()->toArray();
  $id = base64_encode($check_user_id[0]['id']);
  $name =  $check_user_id[0]['first_name'].' '.$check_user_id[0]['last_name'];
  $data  = array(                   
    'name'            => $name,
    'id'              => $id,         
  );
  $result['msg'] = 'Password reset link has been sent!';
  $send =    Mail::send('mail_templates.forget_password',$data,function($message) use ($email){
    $message->to($email, 'Practice gauge')->subject('Practice gauge Reset password');
  }); 
  return $result;
}
public function forgetPwdChange(){
  return view('/auth/forget_password_change');
}
public function saveForgetPwd(Request $request){
  $array = array(
    'password' => Hash::make($request->input('password')),
    'pwd'      => $request->input('password'),
    'status'   => 1
  );
  $id = $request->input('user_id');
  $update = User::where('id',$id)->update($array);
  return $update;
}

}