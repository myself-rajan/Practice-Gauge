<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\UserCompanyMap;
use Illuminate\Support\Facades\Hash;
use App\Models\UserRoles;
use App\Models\UserDetails;
use App\Models\User;
use Session;
use Auth;
use DB;
use Storage;

class UserController extends Controller
{
  public function __construct()
  {
    
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  
  public function viewUsers()
  {
    \View::share('global_page_title', 'Users');
    \View::share('global_menu', 48);
    $user_id = Auth::User()->id;
    $role_id = Auth::User()->role_id;
    
    $company_id = Session::get('company')['company_id'];
    
    $data['roleUser']  = UserRoles::get()->toArray();
    $data['company']  = UserCompanyMap::leftjoin('company', 'company.id', '=', 'user_company_mapping.company_id')->where('user_company_mapping.user_id', $user_id)->select('company.*')->get();

    if($role_id == 1){
      /*$data['users'] = User::join('roles', 'users.role_id', '=', 'roles.id')->where('users.id', '!=',$user_id)->where('roles.id', '!=' , 5)->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*','roles.name')->get();*/
      $data['users'] = User::join('user_company_mapping', 'user_company_mapping.user_id', '=', 'users.id')->where('user_company_mapping.company_id', $company_id)->where('users.role_id', '!=' , 3)->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*')->get();
    }
    else{
      /*if($role_id == 4) { //Firm Admin
        $fetchData = User::where('id', $user_id)->select('parent_id')->first();
        $user_id = $fetchData->parent_id;
      }*/

      $data['users'] = User::join('user_company_mapping', 'user_company_mapping.user_id', '=', 'users.id')->where('user_company_mapping.company_id', $company_id)->where('users.role_id', '!=' , 3)->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*')->get();

      /*
      $data['users'] = Company::join('users', 'users.parent_id', '=', 'company.user_id')->where('company.id', $company_id)->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*')->get();
      $data['users'] = User::join('roles', 'users.role_id', '=', 'roles.id')->where('users.parent_id',$user_id)->where('roles.id', '!=' , 5)->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*','roles.name')->get();*/
    }
    return view('user.viewUsers',$data);
  }

  public function saveUser(Request $request)
  {
    //print_r($request->all());exit();
    $user_id = Auth::User()->id;
    $role_id = Auth::User()->role_id;
    $active = ($request->input('chkUserActive1')) ? '1' : '0';
    $company_ids = $request->input('company_ids');

    $fetchData = User::where('id', $user_id)->select('*')->first();
    if($role_id == 4) { //Firm Admin
      $user_id = $fetchData->parent_id;
    }

    $companyMaping = UserCompanyMap::where('user_id',$user_id)->get();

    $arrayCreate = User::create([
    'first_name'            => $request->input('first_name'),
    'last_name'             => $request->input('last_name'),
    'email'                 => $request->input('email'),
    'role_id'               => $request->input('role'),
    'password'              => Hash::make($request->input('password')),
    'pwd'                   => $request->input('password'),
    'active'                => $active,
    'is_email_verified'     => 1,
    'is_subscription'       => 1,
    'parent_id'             => $user_id,
    'profile_image'         => $request->input('profile_path'),
    'company_name'          => $fetchData->company_name,
    'user_page'             => 1,
    ]);

    $id = $arrayCreate->id;
    $UserDetails = UserDetails::create([
      'user_id' => $id,
      'phone'   => $request->input('contact'),
    ]);


    foreach ($company_ids as $key => $value) {
      $companyMapingCreate = UserCompanyMap::create([
        'user_id'    => $id,
        'company_id' => $value,
      ]);
    }
   return $id;
  }

  public function editUserList(Request $request)
  {
    $id = $request->input('id');

    $company_name = UserCompanyMap::leftjoin('company', 'company.id', '=', 'user_company_mapping.company_id')->where('user_company_mapping.user_id', $id)->select('company.*')->get()->toArray();
    //print_r($company_name);exit();
    

    $editUserList = User::where('users.id',$id)->leftjoin('user_details as ud','users.id','=','ud.user_id')->first();
     
    $data['user'] = ['first_name'=>$editUserList['first_name'],'last_name' => $editUserList['last_name'],'email'=>$editUserList['email'],'contact' =>$editUserList['phone'],'active'=>$editUserList['active'], 'profile_image'=>$editUserList['profile_image'],  'role_id'=>$editUserList['role_id'], 'company_name'=>$company_name, 'pwd'=>$editUserList['pwd']];
     return $data;
  }

  function updateUser(Request $request) 
  {
    $user_id = $request->user_id;
    $active = ($request->input('chkUserActive'))?'1':'0';
    $company_ids = $request->input('company_ids');
    
    $usersArray = array(
      'first_name'=>$request->first_name,
      'last_name'=>$request->last_name,
      'email'=>$request->email,
      'role_id' => $request->input('role'),
      'active' => $active,
      'profile_image' => $request->input('profile_path'),
      'pwd' => $request->pwd,
    );

    $usersDetailsArray = array(
      'phone'=>$request->input('contact'),
    );

    User::where('id', $user_id)->update($usersArray);
    UserDetails::where('user_id', $user_id)->update($usersDetailsArray);

    UserCompanyMap::where('user_id', $user_id)->delete();//To delete previously saved company
    foreach ($company_ids as $key => $value) {
      $companyMapingCreate = UserCompanyMap::create([
        'user_id'    => $user_id,
        'company_id' => $value,
      ]);
/*      $UserCmyData = UserCompanyMap::firstOrNew(array('user_id' => $user_id,'company_id' => $value));
      $UserCmyData->save();
*/    }
  }

  public function saveUserProfile(Request $request)
  {
      $result['error'] = false;
      $data            = $request->all();
      $cropObj         = json_decode($data['imgData']); 
      $cropArr         = array_map('intval', (array)$cropObj);

      if($request->hasFile('profile_picture'))
      {
        $file           = $request->file('profile_picture');
        $imageName      = $request->files->get('profile_picture')->getClientOriginalName();
        $localDesturl   = '/img/users/'.$imageName;

        try {
          $img = \Image::make($file);
          
          $img->crop($cropArr['width'], $cropArr['height'], $cropArr['x'], $cropArr['y']);
          //$img->resize(112, 112);
          $img->save(public_path().$localDesturl);//public_path() - C:\xampp\htdocs\pg\public, $localDesturl - /img/users/pics.PNG
          
          //Check the server comes from local or production server
          if(preg_match('/local.pg./', $_SERVER['SERVER_NAME'])){
            $s3desturl       = $imageName;                    
          }else{
            $s3desturl       = $imageName;     
          }
          
          //Upload S3 Budget
          \Storage::disk('s3')->put($s3desturl, \Storage::disk('local')->get('/users/'.$imageName)); 

        } catch (\Exception $error) {
          return $error->getMessage();
      }
      $result['logoPath'] = $localDesturl;
    }
    
      return $result;
  }    

  function searchUsers(Request $request) {
    $search = $request->search;
    $user_id = Auth::user()->id;
    $role_id = Auth::User()->role_id;

    if($role_id == 1){
      $users = User::join('roles', 'users.role_id', '=', 'roles.id')->where('users.id', '!=',$user_id)->where('roles.id', '!=' , 5)->where('first_name','like', '%'.$search.'%')->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*','roles.name')->get();
    }
    else{
      if($role_id == 4) { //Firm Admin
        $fetchData = User::where('id', $user_id)->select('parent_id')->first();
        $user_id = $fetchData->parent_id;
      }
      $users = User::join('roles', 'users.role_id', '=', 'roles.id')->where('users.parent_id',$user_id)->where('roles.id', '!=' , 5)->where('first_name','like', '%'.$search.'%')->orderBy('users.role_id', 'ASC')->orderBy('users.id', 'DESC')->select('users.*','roles.name')->get();
    }
    //$users = User::join('roles', 'users.role_id', '=', 'roles.id')->where('users.parent_id',$user_id)->where('first_name','like', '%'.$search.'%')->orderBy('users.id', 'DESC')->select('users.*','roles.name')->get(); 
    return view('user.search',['users' => $users]);
  }

  public function editUser(Request $request){
    
    $data['id']       = Auth::user()->id;
    $data['fname']    = Auth::user()->first_name;
    $data['lname']    = Auth::user()->last_name;
    $data['email']    = Auth::user()->email;
    $data['pwd']      = (Auth::user()->pwd);
    $data['profile_image'] = (Auth::user()->profile_image);
    $contact_detail   = UserDetails::where('user_id',Auth::user()->id)->get();
    $data['contact']  = isset($contact_detail[0]->phone) ? $contact_detail[0]->phone : '';
    //print_r($data);die;
    return view('user.editUser',$data);
  }

  public function editUserSvd(Request $request){
    $id = $request->input('id');
    $array = array(
      'first_name' => $request->input('fname'),
      'last_name'  => $request->input('lname'),
      
      'pwd'        => $request->input('pwd'),
      'password'   => Hash::make($request->input('pwd')),
      'profile_image' => $request->input('profile_path'),

    );
    $arrayUpdate = array('phone'    => $request->input('contact'),);
    $update = User::where('id',$id)->update($array);
    $updateDetails = UserDetails::where('user_id',$id)->update($arrayUpdate);
    return $update;
  }
}
