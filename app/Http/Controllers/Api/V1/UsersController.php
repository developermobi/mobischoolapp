<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\CreateAddUserRequest;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Session;
use DB;

DB::enableQueryLog();

class UsersController extends Controller
{

    public  function loginUser(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $userName=$input['user_name'];
        $password=$input['password'];

        try{
            $result = User::authenticate($userName,$password);    
        }
        catch (\Exception $e){
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
         
        //return $result;
        
        if(sizeof($result) > 0)
        {  
           //return response()->json(sizeof($results)); 
            if($result[0]->status == 0){

                $response['status'] = "success";
                $response['code'] = 302;
                $response['message'] = "Your Account is Not Active,Please contact admin";
                return response()->json($response);

            }
            else{
                $response['status'] = "success";
                $response['code'] = 302;
                $response['message'] = "Found";
                $response['data'] = $result;
                return response()->json($response);
            }
                     

        }
        else{
            $response['status'] = "success";
            $response['code'] = 204;
            $response['message'] = "No Content";
            $response['data'] = $result;
            return response()->json($response);
        }      
    }

    public  function resetPassword(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $userName=$input['user_name'];
        $password=$input['password'];
        $newPassword=$input['new_password'];

        try{
            $result = User::checkUser($userName,$password);  
            //return response()->json($result[0]->name);
             if(sizeof($result) > 0){  
                
                $resetPassword = User::resetPassword($userName,$newPassword);

                if($resetPassword){
                    $input['name']=$result[0]->name;
                    $input['email']=$result[0]->email;
                    $input['mobile']=$result[0]->mobile;
                    //return response()->json($input);
                    $email = User::resetPasswordEmail($input);
                    $sms = User::resetPasswordSMS($input);

                    $response['status'] = "success";
                    $response['code'] = 200;
                    $response['message'] = "OK";
                    $response['data'] = $result;
                    return response()->json($response);
                }
                else{
                    $response['status'] = "success";
                    $response['code'] = 304;
                    $response['message'] = "Not Modified";
                    $response['data'] = $result;
                    return response()->json($response);
                } 
                
            }
            else{
                $response['status'] = "success";
                $response['code'] = 204;
                $response['message'] = "No Content";
                $response['data'] = $result;
                return response()->json($response);
            }      
        }
        catch (\Exception $e){
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function checkUserName(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $userName=$input['user_name'];

        try{
            $result = User::checkUserName($userName);  
             if(sizeof($result) > 0){  
             
                    $response['status'] = "success";
                    $response['code'] = 302;
                    $response['message'] = "Found";
                    $response['data'] = $result;
                    return response()->json($response);     
            }
            else{
                $response['status'] = "success";
                $response['code'] = 204;
                $response['message'] = "No Content";
                $response['data'] = $result;
                return response()->json($response);
            }      
        }
        catch (\Exception $e){
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function newPassword(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $userName=$input['user_name'];
        $newPassword=$input['new_password'];

        try{
            $resetPassword = User::resetPassword($userName,$newPassword);

            if($resetPassword){
                $response['status'] = "success";
                $response['code'] = 200;
                $response['message'] = "OK";
                $response['data'] = $resetPassword;
                return response()->json($response);
            }
            else{
                $response['status'] = "success";
                $response['code'] = 304;
                $response['message'] = "Not Modified";
                $response['data'] = $resetPassword;
                return response()->json($response);
            } 
        }
        catch (\Exception $e){
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function studentRegistration(Request $requestData)
    {
        $input = $requestData->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'group_id'=> 'required',   
        ]);    
       
        if($validator->fails()){

            $response['status'] = "failed";
            $response['code'] = 400;
            $response['message'] = $validator->errors()->all();
            return response()->json($response);
        }
       
        $input['password'] = bin2hex(openssl_random_pseudo_bytes(4));

        $parent = array();
        $student = array();

        $parent['password'] = $input['password'];
        $parent['email'] = $input['email'];
        $parent['mobile'] = $input['mobile'];

        $student['name'] = $input['name'];
        $student['user_id'] = $input['user_id'];
        $student['group_id'] = $input['group_id'];

        try{

            $result = User::checkParentLogin($input);

            if(sizeof($result) > 0){ 
                $student['parent_id'] = $result[0]->id;
            }
            else{
                $parentRegistration = User::insertParentLogin($parent);
                $student['parent_id'] = $parentRegistration;
            }

            $insertStudentInfo = User::addStudent($student);

            if($insertStudentInfo)
            {
                $response['status'] = "success";
                $response['code'] = "201";
                $response['message'] = "Created";
                $response['data'] = $insertStudentInfo;
                 return response()->json($response);
            }

        }
        catch (\Exception $e){
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }   
    }

    public function addGroup(Request $requestData)
    {
        $input = $requestData->all();

        $validator = Validator::make($input, [
            'name' => 'required', 
        ]);    
       
        if($validator->fails()){

            $response['status'] = "failed";
            $response['code'] = 400;
            $response['message'] = $validator->errors()->all();
            return response()->json($response);
        }
       
        $group = array();
       
        $group['name'] = $input['name'];
        $group['user_id'] = $input['user_id'];

        try{

            $result = User::checkGroup($group);

            if(sizeof($result) > 0){ 
                $response['status'] = "Client Error";
                $response['code'] = 409;
                $response['message'] = "Conflict";
                $response['data'] = $result;
                return response()->json($response);
            }

            $insertGroupInfo = User::addGroup($group);

            if($insertGroupInfo)
            {
                $response['status'] = "success";
                $response['code'] = 201;
                $response['message'] = "Created";
                $response['data'] = $insertStudentInfo;
                 return response()->json($response);
            }

        }
        catch (\Exception $e){
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }   
    }

}
