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

    public static function authenticate($userName,$password)
    {
       $result = DB::table('users')->where(function ($query) use ($userName) {
            $query->where('email','=',$userName)
                ->orWhere('mobile','=',$userName);
        })->where('password','=',$password)->get();

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
       $result = DB::table('parent_login')->insertGetId($data);
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

        $result = DB::table('parent_login')->where('email','=',$email)->orWhere('mobile','=',$mobile)->get();
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

    //************************************END email and sms functions*****************************************//


}
