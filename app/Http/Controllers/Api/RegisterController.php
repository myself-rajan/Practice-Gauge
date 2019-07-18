<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetails;

use Session;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function usersListGet()
    {
        $users = User::all();
        return response()->json(['users' => $users, 'status' => Response::HTTP_OK]);
    }

    public function saveRegAccountantPost(Request $request)
    {

        if($request->first_name && $request->last_name && $request->email && $request->company_name && $request->practices_count && $request->know_about && $request->states) {

            /*$states = '';
            if($statesData != '')
              $states = implode($statesData, ',');*/

            $confirmation_code = str_random(30);

            $createCPA = User::create([
                'first_name'        => $request->first_name,
                'last_name'         => $request->last_name,
                'email'             => $request->email,
                'company_name'      => $request->company_name,
                'practices_count'   => $request->practices_count,
                'role_id'           => 3,
                'confirmation_code' => $confirmation_code,
                'is_subscription'   => 1,
                'active'            => 0,
                'deleted'           => 0,
            ]);

            $userId = $createCPA->id;    
            if($userId > 0){
             UserDetails::create([
                'user_id'    => $userId,
                'region_id'  => $request->region_id,
                'know_about' => $request->know_about,
                'state_ids'  => $request->states,
              ]);

              /*$data  = array('confirmation_code' => $confirmation_code,'name'=> $name.' '.$lname);
              $result['msg'] = 'Mail Sended successfully';
              Mail::send('mail_templates.confirmationCPA',$data,function($message) use ($email){
                $message->to($email, 'Practice gauge')->subject('Practice Gauge Registration');
              });*/    
            }

            return response()->json(['msg'=>'Accountant created successfully', 'users'=>$createCPA, 'status' => Response::HTTP_OK]);
        } 
        else {
            return response()->json(['msg'=>'Please Provide details for mandatory field', 'status' => Response::HTTP_OK]);
        }
    }

    public function saveRegStdPost(Request $request){
        
        if($request->email && $request->password && $request->organization_name) {
            $password          = Hash::make($request->password);
            $confirmation_code = str_random(30);

            $createStd = User::create([
              'email'             => $request->email,
              'password'          => $password,
              'pwd'               => $request->password,
              'role_id'           => 6,
              'confirmation_code' => $confirmation_code,
              'active'            => 0,
              'company_name'      => $request->organization_name,
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

                /*$users = $email;
                $result['msg'] = 'Mail Sended successfully';
                //Mail::to($users)->send(new Mails($data))->subject('Practice gauge'); 
                Mail::send('mail_templates.mailnotify',$data,function($message) use ($email){
                    $message->to($email, 'Practice gauge')->subject('Practice Gauge Registration');
                });  */             
            }
            return response()->json(['msg'=>'Standard user created successfully', 'users'=>$createStd, 'status' => Response::HTTP_OK]);
        }
        else {
            return response()->json(['msg'=>'Please Provide details for mandatory field', 'status' => Response::HTTP_OK]);
        }

    }
   
}