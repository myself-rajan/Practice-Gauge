<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\UserCompanyMap;
use App\Models\UserRoles;
use App\Models\User;
use Session;
use Auth;
use DB;

class UserRoleController extends Controller
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
    public function viewRoles(){
        \View::share('global_page_title', 'User Roles');
        \View::share('global_menu', 48);
        $user_id = Auth::user()->id;
        $role_id = Auth::user()->role_id;
        $company_id = Session::get('company')['company_id'];

        $userRoles_r= UserRoles::get()->toArray();
        if($role_id == 1) {
            $data['userRoles'] = $userRoles_r;
        }
        else { 
            $data['userRoles'] = UserRoles::whereNotIn('id', array(1))->get()->toArray();
        }

        $userCount = User::join('user_company_mapping', 'user_company_mapping.user_id', '=', 'users.id')->selectRaw('count(users.role_id) as user_count, users.role_id')->groupBy('users.role_id');

        if($role_id != 1) {
            if($role_id == 4) { //Firm Admin
                $fetchData = User::where('id', $user_id)->select('parent_id')->first();
                $user_id = $fetchData->parent_id;
            }
            $userCount->where('user_company_mapping.company_id', $company_id);
            //$userCount->orWhere('id', $user_id);
        }

        $userCount = $userCount->get()->toArray();
        
        $count_value = [];
        foreach($userCount  as $key => $count) {
           $count_value[$count['role_id']] = $count['user_count'];
        }

        $data['user_count'] = $count_value;

        return view('userRoles.userRoles',$data);
    }

    public function userRolesFilters(Request $request){
        $roles = $request->input('userRoles');
        $user_id = Auth::user()->id;
        $role_id = Auth::user()->role_id;
        if($roles != ""){
            if($role_id == 1){
                $data['userRoles'] = UserRoles::where('name','like', '%'.$roles.'%')->get()->toArray();
            }else{
                 $data['userRoles'] = UserRoles::whereIn('id', array(2,3,4,5))->where('name','like', '%'.$roles.'%')->get()->toArray();
            }
        }else{
            if($role_id == 1){
               $data['userRoles']     = UserRoles::get()->toArray();
            }else{
                $data['userRoles']    = UserRoles::whereIn('id', array(2,3,4,5))->get()->toArray();
            }
            
        }  
        $userCount = User::selectRaw('count(users.role_id) as user_count,users.role_id')->groupBy('users.role_id')->where('parent_id',$user_id)->get()->toArray();
        $count_value = [];
        foreach($userCount  as $key => $count)
        {
           $count_value[$count['role_id']] = $count['user_count'];
        }
        $data['user_count'] = $count_value;
       
        return view('userRoles.filterRoles',$data);

    }

   
}
