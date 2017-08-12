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
ini_set('max_input_vars','10000' );

class UsersController extends Controller
{

    public  function login(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $userName=$input['user_name'];
        $password=$input['password'];

        try{
            $result = User::authenticate($userName);    
            
            if(sizeof($result) > 0)
            {
                if(($result[0]->email == $userName || $result[0]->mobile == $userName) && ($result[0]->password == $password))
                {
                    if($result[0]->status == 1)
                    {
                        $response['status'] = "success";
                        $response['code'] = 302;
                        $response['message'] = "Found";
                        $response['data'] = $result;
                        return response()->json($response);
                    }
                    else
                    {
                        $response['status'] = "success";
                        $response['code'] = 403;
                        $response['message'] = "Your Account is Not Active,Please contact admin";
                        return response()->json($response);
                    }
                }
                else
                {
                    $response['status'] = "Failed";
                    $response['code'] = 401;
                    $response['message'] = "In valid user";
                    return response()->json($response);
                }
            }
            else
            {
                $response['status'] = "Failed";
                $response['code'] = 401;
                $response['message'] = "In valid user";
                return response()->json($response);
            }
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
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
                    $response['code'] = 202;
                    $response['message'] = "Accepted";
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
                $response['status'] = "Failed";
                $response['code'] = 401;
                $response['message'] = "Invalid Password";
                $response['data'] = $result;
                return response()->json($response);
            }      
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
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
                $response['status'] = "Failed";
                $response['code'] = 401;
                $response['message'] = "Invalid user name.";
                $response['data'] = $result;
                return response()->json($response);
            }      
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
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
            $result = User::checkUserName($userName);
            $input['name']=$result[0]->name;
            $input['email']=$result[0]->email;
            $input['mobile']=$result[0]->mobile;
            $resetPassword = User::resetPassword($userName,$newPassword);

            if($resetPassword){
                $email = User::newPasswordEmail($input);
                $sms = User::newPasswordSMS($input);
                $response['status'] = "success";
                $response['code'] = 202;
                $response['message'] = "Accepted";
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
            $response['status'] = "Bad Request";
            $response['code'] = 400;
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

            $response['status'] = "Failed";
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
        $parent['user_type'] = 2;

        $student['name'] = $input['name'];
        //$student['user_id'] = $input['user_id'];
        $student['group_id'] = $input['group_id'];

        try{

            $result = User::checkParentLogin($input);

            if(sizeof($result) > 0){ 
                //$student['parent_id'] = $result[0]->id;
                $student['user_id'] = $result[0]->id;
            }
            else{
                $parentRegistration = User::insertParentLogin($parent);
                //$student['parent_id'] = $parentRegistration;
                $student['user_id'] = $parentRegistration;
            }

            $result_student = User::checkStudent($student);

            if(sizeof($result_student) > 0)
            {
                $response['status'] = "Client Error";
                $response['code'] = 409;
                $response['message'] = "Conflict";
                $response['data'] = $result_student;
                return response()->json($response);
            }
            else{
                $insertStudentInfo = User::addStudent($student);

                if($insertStudentInfo)
                {
                    $email = User::registrationEmail($input);
                    $sms = User::registrationSMS($input);

                    $response['status'] = "success";
                    $response['code'] = "201";
                    $response['message'] = "Created";
                    $response['data'] = $insertStudentInfo;
                     return response()->json($response);
                }
            }
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
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

            $response['status'] = "Failed";
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
                $response['data'] = $insertGroupInfo;
                 return response()->json($response);
            }

        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }   
    }

    public  function userGroup(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $userId=$input['user_id'];

        try{
            $result = User::getUserGroup($userId);  
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
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function userGroupStudent(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        try{
            $result = User::getUserGroupStudent($input);  

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
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function updateStudent(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);
        $validator = Validator::make($input, [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'group_id'=> 'required',   
        ]);    
       
        if($validator->fails()){

            $response['status'] = "Failed";
            $response['code'] = 400;
            $response['message'] = $validator->errors()->all();
            return response()->json($response);
        }
        
        try{

            $student = array();
            $parent = array();

            $student['name'] = $input['name'];
            $student['group_id'] = $input['group_id'];
            $student_id = $input['id'];

            $parent['email'] = $input['email'];
            $parent['mobile'] = $input['mobile'];

            $parentIdByStudentId = User::getParentId($student_id);
            $parent_id = $parentIdByStudentId[0]->user_id;
            
            //return response()->json($parentIdByStudentId);

            $updateStudentData = User::updateStudent($student,$student_id);

            $updateParentData = User::updateParent($parent,$parent_id);

            if($updateStudentData){

                $response['status'] = "success";
                $response['code'] = 200;
                $response['message'] = "OK";
                $response['data'] = $updateStudentData;

                if($updateParentData){
                    $password = User::getPassword($parent_id);
                    $input['password'] = $password[0]->password;
                    $email = User::updateStudentEmail($input);
                    $sms = User::updateStudentSMS($input);

                    $response['status'] = "success";
                    $response['code'] = 200;
                    $response['message'] = "OK";
                    $response['data'] = $updateParentData;
                 }
            }
            else{
                 if($updateParentData){
                    $password = User::getPassword($parent_id);
                    $input['password'] = $password[0]->password;
                    $email = User::updateStudentEmail($input);
                    $sms = User::updateStudentSMS($input);

                    $response['status'] = "success";
                    $response['code'] = 200;
                    $response['message'] = "OK";
                    $response['data'] = $updateParentData;
                 }
                 else{
                    $response['status'] = "success";
                    $response['code'] = 304;
                    $response['message'] = "Not Modified";
                    $response['data'] = $updateStudentData;
                 } 
            } 
            return response()->json($response);
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function deleteStudent(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);   
         $student_id = $input['student_id'];
        try{

            $parentIdByStudentId = User::getParentId($student_id);
            $parent_id = $parentIdByStudentId[0]->user_id;
            
            $countStudents = User::countStudentsByParent($parent_id);
            //return response()->json($student_id);
            if(count($countStudents)==1)
            {
                $deleteParent = User::deleteParent($parent_id);
            }
            
            $deleteStudent = User::deleteStudent($student_id);
            
            //return response()->json($parentIdByStudentId);

            if($deleteStudent){

                $response['status'] = "success";
                $response['code'] = 200;
                $response['message'] = "OK";
                $response['data'] = $deleteStudent;
            }
            else{
                 
                $response['status'] = "success";
                $response['code'] = 304;
                $response['message'] = "Already Deleted";
                $response['data'] = $updateStudentData;
            } 
            return response()->json($response);
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function deleteGroup(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);   
         $group_id = $input['group_id'];
        try{
            
            $deleteGroup = User::deleteGroup($group_id);
            
            //return response()->json($parentIdByStudentId);

            if($deleteGroup){

                $response['status'] = "success";
                $response['code'] = 200;
                $response['message'] = "OK";
                $response['data'] = $deleteGroup;
            }
            // else{
                 
            //     $response['status'] = "success";
            //     $response['code'] = 304;
            //     $response['message'] = "Already Deleted";
            //     $response['data'] = $updateStudentData;
            // } 
            return response()->json($response);
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function updateGroup(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);    
       
        if($validator->fails()){

            $response['status'] = "Failed";
            $response['code'] = 400;
            $response['message'] = $validator->errors()->all();
            return response()->json($response);
        }

       

        try{
            
            $updateGroup = User::updateGroup($input);
            
            //return response()->json($parentIdByStudentId);

            if($updateGroup){

                $response['status'] = "success";
                $response['code'] = 200;
                $response['message'] = "OK";
                $response['data'] = $updateGroup;
            }
            else{
                 
                $response['status'] = "success";
                $response['code'] = 304;
                $response['message'] = "Already Updated";
                $response['data'] = $updateGroup;
            } 
            return response()->json($response);
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function groupNotification(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);
        $data = array();
        $data['group_id'] = $input['group_id'];   
        $data['notification_type'] = $input['notification_type'];
        
        //return response()->json($data);
        try{

            $group = User::getGroupData($data);

            $emailData = array();
            $smsData = array();
            $imageData = array();
            //return response()->json($group);
            if($data['notification_type'] == 1) //1.Email
            {
                $data['subject'] = $input['subject'];
                $data['message'] = $input['message'];

                foreach ($group as $key => $value) {
                    $emailData['subject'] = $data['subject'];
                    $emailData['message'] = $data['message'];
                    $emailData['type'] = $data['notification_type'];
                    $emailData['email'] = $group[$key]->email;
                    $emailData['mobile'] = $group[$key]->mobile;
                    $emailData['group_id'] = $group[$key]->group_id;
                    $emailData['student_id'] = $group[$key]->id;
                    $emailData['parent_id'] = $group[$key]->parent_id;
                    $emailData['user_id'] = $group[$key]->user_id;
                    $emailData['name'] = $group[$key]->name;

                    $insertNotification = User::insertNotification($emailData);

                    $emailData['notification_id'] = $insertNotification;

                    $insertNotificationSent = User::insertNotificationSent($emailData);
                    //return response()->json($insertNofification);
                    $email = User::groupEmail($emailData);
                } 

                if($insertNotificationSent)
                {
                    $response['status'] = "success";
                    $response['code'] = "201";
                    $response['message'] = "Created";
                    $response['data'] = $insertNotificationSent;
                    return response()->json($response);
                }      
            }

            if($data['notification_type'] == 2) //2.SMS
            {
                $data['message'] = $input['message'];
                //unset($input['subject']);
                foreach ($group as $key => $value) {
                    $smsData['message'] = $data['message'];
                    $smsData['type'] = $data['notification_type'];
                    $smsData['mobile'] = $group[$key]->mobile;
                    $smsData['email'] = $group[$key]->email;
                    $smsData['group_id'] = $group[$key]->group_id;
                    $smsData['student_id'] = $group[$key]->id;
                    $smsData['parent_id'] = $group[$key]->parent_id;
                    $smsData['user_id'] = $group[$key]->user_id;
                    $smsData['name'] = $group[$key]->name;

                    $insertNotification = User::insertNotification($smsData);

                    $smsData['notification_id'] = $insertNotification;

                    $insertNotificationSent = User::insertNotificationSent($smsData);

                    $sms = User::groupSMS($smsData);
                }

                if($insertNotificationSent)
                {
                    $response['status'] = "success";
                    $response['code'] = "201";
                    $response['message'] = "Created";
                    $response['data'] = $insertNotificationSent;
                     return response()->json($response);
                }    
                
            }

            if($data['notification_type'] == 3) //3.Image
            {
                $data['image'] = $input['image'];
                $data['subject'] = $input['subject'];
                //return response()->json($group);
                foreach ($group as $key => $value) {
                    $imageData['image'] = $data['image'];
                    $imageData['type'] = $data['notification_type'];
                    $imageData['subject'] = $data['subject'];
                    $imageData['mobile'] = $group[$key]->mobile;
                    $imageData['email'] = $group[$key]->email;
                    $imageData['group_id'] = $group[$key]->group_id;
                    $imageData['student_id'] = $group[$key]->id;
                    $imageData['parent_id'] = $group[$key]->parent_id;
                    $imageData['user_id'] = $group[$key]->user_id;
                    $imageData['name'] = $group[$key]->name;
                    
                    $insertNotification = User::insertNotification($imageData);

                    $imageData['notification_id'] = $insertNotification;

                    $insertNotificationSent = User::insertNotificationSent($imageData);

                }
                
                if($insertNotificationSent)
                {
                    $response['status'] = "success";
                    $response['code'] = "201";
                    $response['message'] = "Created";
                    $response['data'] = $insertNotificationSent;
                    return response()->json($response);
                }    
            }
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public  function studentNotification(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);
        $data = array();
        $data['group_id'] = $input['group_id'];
        $data['student_id'] = $input['student_id'];   
        $data['notification_type'] = $input['notification_type'];
        
        //return response()->json($data);
        try{

            $selectedStudents = User::getSelectedStudentsData($data);
            //return response()->json($selectedStudents);
            $emailData = array();
            $smsData = array();
            $imageData = array();
            //return response()->json($group);
            if($data['notification_type'] == 1) //1.Email
            {
                $data['subject'] = $input['subject'];
                $data['message'] = $input['message'];

                foreach ($selectedStudents as $key => $value) {
                    $emailData['subject'] = $data['subject'];
                    $emailData['message'] = $data['message'];
                    $emailData['type'] = $data['notification_type'];
                    $emailData['email'] = $selectedStudents[$key]->email;
                    $emailData['mobile'] = $selectedStudents[$key]->mobile;
                    $emailData['group_id'] = $selectedStudents[$key]->group_id;
                    $emailData['student_id'] = $selectedStudents[$key]->id;
                    $emailData['parent_id'] = $selectedStudents[$key]->parent_id;
                    $emailData['user_id'] = $selectedStudents[$key]->user_id;
                    $emailData['name'] = $selectedStudents[$key]->name;

                    $insertNotification = User::insertNotification($emailData);

                    $emailData['notification_id'] = $insertNotification;

                    $insertNotificationSent = User::insertNotificationSent($emailData);
                    //return response()->json($insertNofification);
                    $email = User::groupEmail($emailData);
                } 

                if($insertNotificationSent)
                {
                    $response['status'] = "success";
                    $response['code'] = "201";
                    $response['message'] = "Created";
                    $response['data'] = $insertNotificationSent;
                    return response()->json($response);
                }      
            }

            if($data['notification_type'] == 2) //2.SMS
            {
                $data['message'] = $input['message'];
                //unset($input['subject']);
                foreach ($selectedStudents as $key => $value) {
                    $smsData['message'] = $data['message'];
                    $smsData['type'] = $data['notification_type'];
                    $smsData['mobile'] = $selectedStudents[$key]->mobile;
                    $smsData['email'] = $selectedStudents[$key]->email;
                    $smsData['group_id'] = $selectedStudents[$key]->group_id;
                    $smsData['student_id'] = $selectedStudents[$key]->id;
                    $smsData['parent_id'] = $selectedStudents[$key]->parent_id;
                    $smsData['user_id'] = $selectedStudents[$key]->user_id;
                    $smsData['name'] = $selectedStudents[$key]->name;

                    $insertNotification = User::insertNotification($smsData);

                    $smsData['notification_id'] = $insertNotification;

                    $insertNotificationSent = User::insertNotificationSent($smsData);

                    $sms = User::groupSMS($smsData);
                }

                if($insertNotificationSent)
                {
                    $response['status'] = "success";
                    $response['code'] = "201";
                    $response['message'] = "Created";
                    $response['data'] = $insertNotificationSent;
                     return response()->json($response);
                }    
                
            }

            if($data['notification_type'] == 3) //3.Image
            {
                $data['image'] = $input['image'];
                $data['subject'] = $input['subject'];

                $imgarr = explode(',', $data['image']);

                if(!isset($imgarr[1])){
                    $response['status'] = "Bad Request";
                    $response['code'] = 400;
                    $response['message'] = 'Error on post data image. String is not the expected string.';
                    return response()->json($response);
                }

                $image = base64_decode($imgarr[1]);
                
                if(!is_null($image)){
                    $file_name = time().'.'.'JPEG';
                    $file = public_path('/images/').$file_name;
                    if(file_exists($file)){
                        $response['status'] = "Conflict";
                        $response['code'] = 409;
                        $response['message'] = 'Image already exists on server.';
                        $response['data'] = $file;
                        return response()->json($response);
                    }
                    if(file_put_contents($file, $image) !== false){

                       foreach ($selectedStudents as $key => $value) {
                            $imageData['image'] = $file_name;
                            $imageData['type'] = $data['notification_type'];
                            $imageData['subject'] = $data['subject'];
                            $imageData['mobile'] = $selectedStudents[$key]->mobile;
                            $imageData['email'] = $selectedStudents[$key]->email;
                            $imageData['group_id'] = $selectedStudents[$key]->group_id;
                            $imageData['student_id'] = $selectedStudents[$key]->id;
                            $imageData['parent_id'] = $selectedStudents[$key]->parent_id;
                            $imageData['user_id'] = $selectedStudents[$key]->user_id;
                            $imageData['name'] = $selectedStudents[$key]->name;
                            
                            $insertNotification = User::insertNotification($imageData);

                            $imageData['notification_id'] = $insertNotification;

                            $insertNotificationSent = User::insertNotificationSent($imageData);

                        }
                        
                        if($insertNotificationSent)
                        {
                            $response['status'] = "success";
                            $response['code'] = "201";
                            $response['message'] = "Created";
                            $response['data'] = $insertNotificationSent;
                            return response()->json($response);
                        } 
                    }
                    else{
                        $response['status'] = "Bad Request";
                        $response['code'] = 400;
                        $response['message'] = 'Error writing file to disk';
                        return response()->json($response);
                    }
                }
                else{
                    $response['status'] = "Bad Request";
                    $response['code'] = 400;
                    $response['message'] = 'Error decoding base64 string.';
                    return response()->json($response);
                }
                //return response()->json($group);   
            }
        }
        catch (\Exception $e){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function fileUpload(Request $request)
    {
       $input = $request->all();
       //return response()->json($input['image']);
        // $this->validate($request, [
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        $imgarr = explode(',', $input['image']);

        if(!isset($imgarr[1])){
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = 'Error on post data image. String is not the expected string.';
            return response()->json($response);
        }

        $image = base64_decode($imgarr[1]);
        
        if(!is_null($image)){
            $file = public_path('/images/').time().'.'.'JPEG';
            if(file_exists($file)){
                $response['status'] = "Conflict";
                $response['code'] = 409;
                $response['message'] = 'Image already exists on server.';
                $response['data'] = $file;
                return response()->json($response);
            }
            if(file_put_contents($file, $image) !== false){
                $response['status'] = "success";
                $response['code'] = 200;
                $response['message'] = 'Image saved to server';
                $response['data'] = $file;
                return response()->json($response);
            }
            else{
                $response['status'] = "Bad Request";
                $response['code'] = 400;
                $response['message'] = 'Error writing file to disk';
                return response()->json($response);
            }
        }
        else{
            $response['status'] = "Bad Request";
            $response['code'] = 400;
            $response['message'] = 'Error decoding base64 string.';
            return response()->json($response);
        }
        //$image = $request->file('image');
        //return $image;
        //$input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        //return $input;
        //$destinationPath = public_path('/images');
        //$image->move($destinationPath, $input['imagename']);

        //return $this->postImage->add($input);

         
    }

}
