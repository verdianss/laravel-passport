<?php

namespace App\Http\Controllers;


use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    /**
     * to request token using laravel passport
     *
     * @param Request $request
     * @return void
     */
    public function requestToken(Request $request) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('/oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '3',
                'client_secret' => 'Ir5ZdRCg1K3z7zRdnJDavwHryf29VGWH6gW2qh7D',
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }


    /**
     * To get token if access granted
     *
     * @param Request $request
     * @return result
     */
    public function accessToken(Request $request) {
        $user = User::where("email",$request->email)->first();
        if($user){
            if (Hash::check($request->password,$user->password)) {
                return $this->prepareResult(true, ["accessToken" => $user->createToken('Todo App')->accessToken], [],"User Verified");
            }else{
                return $this->prepareResult(false, [], ["password" => "Wrong passowrd"],"Password not matched");  
            }
        }else{
            return $this->prepareResult(false, [], ["email" => "Unable to find user"],"User not found");
        }
    }
    
    
    /**
    * Get a validator
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $type
    * @return \Illuminate\Contracts\Validation\Validator
    */
    public function validations($request,$type){
        $errors = [ ];
        $error = false;
        if($type == "login"){
            $validator = Validator::make($request->all(),[
                            'email' => 'required|email|max:255',
                            'password' => 'required',
                        ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        } 
        return ["error" => $error,"errors"=>$errors];
    }
    
    
    /**
    * Display a listing of the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function prepareResult($status, $data, $errors,$msg) {
        return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
    }
}
