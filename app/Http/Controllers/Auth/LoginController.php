<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/company/select';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    protected function credentials(Request $request)
    {   
        $active = 1;
        $email = $request->{$this->username()};
        $userData = User::where('email', $email)->select('users.*')->first();

        if(isset($userData)) {
            if($userData->parent_id) {
              $subcrip = User::where('id', $userData->parent_id)->select('is_subscription')->first();
                if($subcrip->is_subscription == 1)
                    $active = 1;
                else
                    $active = 0;
            }
            else {
                $subcrip = User::where('id', $userData->id)->select('is_subscription')->first();
                if($subcrip->is_subscription == 1)
                    $active = 1;
                else
                    $active = 0;
            }
        }
        //echo "<pre>";print_r($active);exit();
        return [
            'email'             => $request->{$this->username()},
            'password'          => $request->password,
            'is_email_verified' => '1',
            'active'            => $active,
        ];
    }
}
