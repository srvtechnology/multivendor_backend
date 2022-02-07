<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Laravel\Passport\RefreshTokenRepository;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required',
        //     'c_password' => 'required|same:password',
        // ]);
   
        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());       
        // }
   
        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $user = User::create($input);
        // $success['token'] =  $user->createToken('MyApp')->accessToken;
        // $success['name'] =  $user->name;
        $ins=new User;
    	$ins->name = $request->back_name;
    	$ins->email = $request->back_email;
    	$ins->password = \Hash::make($request->back_password);
    	$ins->save();
    	$token = $ins->createToken('LaravelAuthApp')->accessToken;
    	return response()->json(['msg'=>'inserted']);
   
        //return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $token =  $user->createToken('MyApp')-> accessToken; 
           // $success['name'] =  $user->name;
             $msg="authorized";
             
             return response()->json(['token' => $token,'msg'=>$msg], 200);
            //return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return  response()->json(['msg'=>'Unauthorised']);
        } 
    }







    public function getuser(Request $request){
         return response()->json(auth()->user());
    }


      public function logout(Request $request){
       $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }




}
