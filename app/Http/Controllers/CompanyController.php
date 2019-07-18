<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\UserCompanyMap;
use App\Models\User;
use Session;
use Auth;
use DB;

class CompanyController extends Controller
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
    public function selectOrganization()
    {
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();

        $organization_select = User::where('role_id', '=', 3)->where('deleted', 0)->get()->toArray();

        $data['organization_select'] = '';
        if($organization_select != ''){
            $data['organization_select'] = $organization_select;
        }
       
        $data['user'] = Auth::user();  

        if($user->role_id == 1) {
            return view('company.select_organization')->with($data);
        } else {
            return redirect()->route('select_company');
        }
    }

    public function selectCompany()
    {
        if(empty($_GET['org_id'])) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = $_GET['org_id'];
        }

        Session::put('company', [ 'org_id' => $user_id]);
        $data['org_id'] = $user_id;  

        $data['user'] = Auth::user();  
        //$check_user_mapping = UserCompanyMap::where('user_id', $user_id)->where('deleted',0)->get()->toArray();
        //$user = User::where('id', $user_id)->first();
        
        $company_select = UserCompanyMap::join('company', 'user_company_mapping.company_id', '=', 'company.id')->select('company.*')->where('company.deleted', 0)->where('user_company_mapping.deleted',0)->where('user_company_mapping.user_id',$user_id)->get()->toArray();
        
        $data['company_select'] ='';
        if(!empty($company_select)){
            $data['company_select'] = $company_select;
        }
       
        if((Auth::user()->role_id == 1 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4) && count($company_select) == 0){
            Session::put('company', [ 'company_id' => 0, 'global_company_name' => '']);
           return redirect()->route('available_practices');
        }

        return view('company.select_company')->with($data);
        
    }
    public function companyRedirect(Request $request)
    {
        $result['error'] = false;

        $org_id = $request->org_id;

        $sh = Company::find($request->company_id)->touch(); 
       
        $checkCompany = Company::where('id', $request->company_id)->first();
        $qbo_connection_check = $checkCompany->qbo_connection;

        $global_company_name = $checkCompany->name;
    
        Session::put('company', [ 'company_id' => $request->company_id, 'global_company_name' => $global_company_name,'qbo_connection' => $qbo_connection_check, 'org_id' => $org_id]);
        $result['company_id'] = $request->company_id;

        //Download image from s3 bucket
        $this->downloadImageFroms3($org_id);
        // $qbo_connection      = $checkCompany->qbo_connection;
        // $steps               = $checkCompany->reg_steps;
        // $company_logo        = $checkCompany->logo;
        //print_r($result);die;
        // $result['qbo_connection'] = $qbo_connection;
        // $result['steps']          = $steps;
        // $result['company_logo']   = $company_logo;
        
       return $result;
    }

     public function searchCompanies(Request $request)
    {
        //$user_id    = Auth::user()->id;
        $search_key = $request->search_key;
        $data['search_from'] = $request->search_from;

        if(isset(Session::get('company')['org_id'])) {
           $user_id = Session::get('company')['org_id'];
        } else {
            $user_id = Auth::user()->id;
        }

        /*if(Auth::user()->role_id == 1 || Auth::user()->role_id == 4){
            $companies = Company::where('deleted', 0)->where('name', 'LIKE', '%'.$search_key.'%')->orderBy('updated_at', 'DESC')->get();
        } else {*/
            $companies  = UserCompanyMap::join('company', 'user_company_mapping.company_id', '=', 'company.id')
                        ->select('company.*')
                        ->where('user_company_mapping.user_id', $user_id)->where('company.deleted', 0)->where('user_company_mapping.deleted', 0)
                        ->where('company.name', 'LIKE', '%'.$search_key.'%')
                        //->where('company.updated_at', 'LIKE', '%'.$search_key.'%')
                        ->get();
        //}
        $data['companies'] = $companies;
        return view('company.searchCompanyList')->with($data);
    }


    public function searchOrganization(Request $request)
    {
        $user_id    = Auth::user()->id;
        $search_key = $request->search_key;
        $data['search_from'] = $request->search_from;

        $organization  = User::select('users.*')
                        ->where('users.role_id', 3)->where('users.deleted', 0)
                        ->where('users.company_name', 'LIKE', '%'.$search_key.'%')
                        ->get();
        
        $data['organization'] = $organization;
        return view('company.searchOrganizationList')->with($data);
    }

     //view all Companies page
    public static function viewAllCompanies()
    {
        //\View::share('global_page_title', 'Select Company');
        //\View::share('global_menu', 48);
        if(isset(Session::get('company')['org_id'])) {
           $user_id = Session::get('company')['org_id'];
        } else {
            $user_id = Auth::user()->id;
        }
        $companies = UserCompanyMap::join('company', 'user_company_mapping.company_id', '=', 'company.id')
                             ->select('company.*')
                             ->where('user_company_mapping.user_id', $user_id)->where('company.deleted', 0)->where('user_company_mapping.deleted', 0)->orderBy('company.updated_at', 'DESC')->get();
        return $companies;
    }


    public function downloadImageFroms3($user_id)
    {
        $msg = 'Error while downloading';

        $userProfile = User::where('id' , $user_id)->pluck('profile_image')->first();///img/users/thumb-1920-1012761.jpg
        if($userProfile) {
            $imageName     = str_replace("/img/users/", "", $userProfile);            

            if(preg_match('/local.pg./', $_SERVER['SERVER_NAME'])){
                $desturl       = $imageName;                    
            }else{
                $desturl       = $imageName;     
            }

            $check      = \Storage::disk('s3')->exists($desturl);
            
            $checkLocal = \Storage::disk('local')->exists('users/'.$imageName);

            if($check && !$checkLocal){
                \Storage::disk('local')->put('users/'.$imageName, \Storage::disk('s3')->get($desturl));          
                $msg = 'Downloaded!';
            }
            else {
                $msg = 'Alredy exist in local';
            }
        }
        else {
            $msg = 'There is no logo to download';
        }

        return $msg;
    }
}
