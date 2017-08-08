<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\CreateAddUserRequest;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Student;
use Session;
use DB;

DB::enableQueryLog();

class StudentController extends Controller
{

    public  function userStudentList(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $user_id=$input['user_id'];

        try{
            $result = Student::getStudent($user_id);    
            
            if(sizeof($result) > 0)
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
                $response['code'] = 204;
                $response['message'] = "No Content";
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

    public  function getStudentById(Request $requestData)
    {
        $input=$requestData->all();
        //return response()->json($input);

        $student_id=$input['student_id'];

        try{
            $result = Student::getSingleStudent($student_id);    
            
            if(sizeof($result) > 0)
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
                $response['code'] = 204;
                $response['message'] = "No Content";
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

}
