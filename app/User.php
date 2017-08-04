<?php

namespace App;

use DB;
use Mail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function authenticate($userName)
    {
       $result = DB::table('users')->where(function ($query) use ($userName) {
            $query->where('email','=',$userName)
                ->orWhere('mobile','=',$userName);
        })->get();

        return $result; 
    }

    public static function checkUser($userName,$password)
    {
       $result = DB::table('users')->where(function ($query) use ($userName) {
            $query->where('email','=',$userName)
                ->orWhere('mobile','=',$userName);
        })->where('password','=',$password)->where('status','=',1)->get();

        return $result; 
    }

    public static function resetPassword($userName,$newPassword)
    {
        $new_password['password'] = $newPassword;
        $update= DB::table('users')->where(function ($query) use ($userName) {
            $query->where('email','=',$userName)
                ->orWhere('mobile','=',$userName);
        })->update($new_password);

        return $update;
    }

    public static function updateStudent($data,$student_id)
    {  
        $update= DB::table('student')->where('id','=',$student_id)->update($data);
        return $update;
    }

    public static function updateParent($data,$parent_id)
    {
        $update= DB::table('users')->where('id','=',$parent_id)->update($data);
        return $update;
    }
    public static function checkUserName($userName)
    {
       $result = DB::table('users')->where(function ($query) use ($userName) {
            $query->where('email','=',$userName)
                ->orWhere('mobile','=',$userName);
        })->where('status','=',1)->get();

        return $result; 
    }

    public static function insertParentLogin($data)
    {
       $result = DB::table('users')->insertGetId($data);
        return $result; 
    }

    public static function addStudent($data)
    {
       $result = DB::table('student')->insert($data);
        return $result; 
    }

    public static function checkParentLogin($data)
    {
        $email = $data['email'];
        $mobile = $data['mobile'];

        $result = DB::table('users')->where('email','=',$email)->orWhere('mobile','=',$mobile)->get();
        return $result; 
    }

    public static function checkStudent($data)
    {
        $name = $data['name'];
        $parent_id = $data['parent_id'];

        $result = DB::table('student')->where('name','=',$name)->where('parent_id','=',$parent_id)->get();
        return $result; 
    }

    public static function checkGroup($data)
    {
        $name = $data['name'];
        $user_id = $data['user_id'];

        $result = DB::table('group')->where('name','=',$name)->where('user_id','=',$user_id)->get();
        return $result; 
    }

    public static function addGroup($data)
    {
       $result = DB::table('group')->insert($data);
        return $result; 
    }

    public static function getUserGroup($user_id)
    {
        $result = DB::table('group')->where('user_id','=',$user_id)->where('status','=',1)->get();
        return $result; 
    }

    public static function getUserGroupStudent($data)
    {
        $group_id = $data['group_id'];
        $user_id = $data['user_id'];

        $result = DB::table('student')->where('user_id','=',$user_id)->where('group_id','=',$group_id)->where('status','=',1)->get();
        return $result; 
    }

    public static function getPassword($parent_id)
    {
        $result = DB::table('parent_login')->where('id','=',$parent_id)->get();
        return $result; 
    }

    public static function getParentId($student_id)
    {
        $result = DB::table('student')->where('id','=',$student_id)->get();
        return $result; 
    }

    public static function countStudentsByParent($parent_id)
    {
        $result = DB::table('student')->where('parent_id','=',$parent_id)->get();
        return $result; 
    }

    public static function deleteParent($parent_id)
    {
        $data = array();
        $data['status'] = 0;
        $update= DB::table('users')->where('id','=',$parent_id)->update($data);
        return $update;
    }

    public static function deleteStudent($student_id)
    {
        $data = array();
        $data['status'] = 0;
        $update= DB::table('student')->where('id','=',$student_id)->update($data);
        return $update;
    }

    public static function getGroupData($data)
    {
        $group_id = $data['group_id'];
        $result = DB::table('student')
        ->join('parent_login', 'parent_login.id', '=', 'student.parent_id')
        ->select('student.*', 'parent_login.email', 'parent_login.mobile')
        ->whereIn('group_id',[$group_id])->get();
        return $result; 
    }

    public static function insertNotification($data)
    {
        unset($data['name']);
        unset($data['email']);
        unset($data['mobile']);
        //return $data;
        $result = DB::table('notification')->insert($data);
        return $result; 
    }


//************************************Start email and sms functions*****************************************//

public static function resetPasswordEmail($userInfo)
{

    $res = Mail::send('emails.resetPassword',['userInfo' => $userInfo], function($message) use ($userInfo){

    $message->from('no-reply@mobisofttech.co.in', 'Mobisoft Technology');
    $message->to($userInfo['email'])->subject('Reset password');
    $message->cc('ziaurrahman.a@mobisofttech.co.in');
    $message->bcc('tushar.k@mobisofttech.co.in');      

    });

    return $res;
}

public static function resetPasswordSMS($userInfo)
{
    $user=env('SMS_USERNAME');
    $pwd=env('SMS_PASSWORD');
    $senderID=env('MOBSFT'); 

    $name = $userInfo['name'];
    $userName = $userInfo['user_name'];
    $newPassword = $userInfo['new_password'];
    $mobile = $userInfo['mobile'];

    $msgtxt="Dear ".$name.", User Name is ".$userName." .\nNew Password is ".$newPassword.".\nRegards,\nMobisoft Technology";
    $msgtxt=urlencode($msgtxt);

    $sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendmultiplesms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile.",".$abiMobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
    return file_get_contents($sms_url);
}

public static function newPasswordEmail($userInfo)
{

    $res = Mail::send('emails.newPassword',['userInfo' => $userInfo], function($message) use ($userInfo){

    $message->from('no-reply@mobisofttech.co.in', 'Mobisoft Technology');
    $message->to($userInfo['email'])->subject('Forgot password');
    $message->cc('ziaurrahman.a@mobisofttech.co.in');
    $message->bcc('tushar.k@mobisofttech.co.in');      

    });

    return $res;
}

public static function newPasswordSMS($userInfo)
{
    $user=env('SMS_USERNAME');
    $pwd=env('SMS_PASSWORD');
    $senderID=env('MOBSFT'); 

    $name = $userInfo['name'];
    $userName = $userInfo['user_name'];
    $newPassword = $userInfo['new_password'];
    $mobile = $userInfo['mobile'];

    $msgtxt="Dear ".$name.", User Name is ".$userName." .\nNew Password is ".$newPassword.".\nRegards,\nMobisoft Technology";
    $msgtxt=urlencode($msgtxt);

    $sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendmultiplesms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile.",".$abiMobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
    return file_get_contents($sms_url);
}

public static function registrationEmail($userInfo)
{

    $res = Mail::send('emails.registration',['userInfo' => $userInfo], function($message) use ($userInfo){

    $message->from('no-reply@mobisofttech.co.in', 'Mobisoft Technology');
    $message->to($userInfo['email'])->subject('Forgot password');
    $message->cc('ziaurrahman.a@mobisofttech.co.in');
    $message->bcc('tushar.k@mobisofttech.co.in');      

    });

    return $res;
}

public static function registrationSMS($userInfo)
{
    $user=env('SMS_USERNAME');
    $pwd=env('SMS_PASSWORD');
    $senderID=env('MOBSFT'); 

    $name = $userInfo['name'];
    $userName = $userInfo['email']." / ".$userInfo['mobile'];
    $password = $userInfo['password'];
    $mobile = $userInfo['mobile'];

    $msgtxt="Dear ".$name.", Your registration successfully done.\nUser Name is ".$userName." .\nPassword is ".$password.".\nRegards,\nMobisoft Technology";
    $msgtxt=urlencode($msgtxt);

    $sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendmultiplesms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile.",".$abiMobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
    return file_get_contents($sms_url);
}

public static function updateStudentEmail($userInfo)
{

    $res = Mail::send('emails.updateStudent',['userInfo' => $userInfo], function($message) use ($userInfo){

    $message->from('no-reply@mobisofttech.co.in', 'Mobisoft Technology');
    $message->to($userInfo['email'])->subject('Update Details');
    $message->cc('ziaurrahman.a@mobisofttech.co.in');
    $message->bcc('tushar.k@mobisofttech.co.in');      

    });

    return $res;
}

public static function updateStudentSMS($userInfo)
{
    $user=env('SMS_USERNAME');
    $pwd=env('SMS_PASSWORD');
    $senderID=env('MOBSFT'); 

    $name = $userInfo['name'];
    $userName = $userInfo['email']." / ".$userInfo['mobile'];
    $password = $userInfo['password'];
    $mobile = $userInfo['mobile'];

    $msgtxt="Dear ".$name.", Your updated detais are.\nUser Name is ".$userName." .\nPassword is ".$password.".\nRegards,\nMobisoft Technology";
    $msgtxt=urlencode($msgtxt);

    $sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendmultiplesms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile.",".$abiMobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
    return file_get_contents($sms_url);
}

public static function groupEmail($userInfo)
{

    $res = Mail::send('emails.groupEmail',['userInfo' => $userInfo], function($message) use ($userInfo){

    $message->from('no-reply@mobisofttech.co.in', 'Mobisoft Technology');
    $message->to($userInfo['email'])->subject($userInfo['subject']);
    $message->cc('ziaurrahman.a@mobisofttech.co.in');
    $message->bcc('tushar.k@mobisofttech.co.in');      

    });

    return $res;
}

public static function groupSMS($userInfo)
{
    $user=env('SMS_USERNAME');
    $pwd=env('SMS_PASSWORD');
    $senderID=env('MOBSFT'); 

    $name = $userInfo['name'];
    $message = $userInfo['message'];
    $mobile = $userInfo['mobile'];

    $msgtxt="Dear ".$name.",\n".$message.".\nRegards,\nMobisoft Technology";
    $msgtxt=urlencode($msgtxt);

    $sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendmultiplesms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile.",".$abiMobile."&type=1&message=".$msgtxt;
        //$sms_url= "http://makemysms.in/api/sendsms.php?username=".$user."&password=".$pwd."&sender=".$senderID."&mobile=".$mobile."&type=1&message=".$msgtxt;
    return file_get_contents($sms_url);
}


    //************************************END email and sms functions*****************************************//


}
