<?php
namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Hash;
use Mail;
use Illuminate\Support\Facades\Input;

use Cache;
use Log;

class AjaxController extends Controller
{


    public function getShowStatus()
    {
        return zcjy_callback_data(getSettingValueByKey('sign_word_show_status'));
    }
    
    public function getEndTime()
    {
        return zcjy_callback_data(optional(app('common')->nowCanSign())->date);
    }

    public function getLoginStatus($user_id)
    {
        $user = User::find($user_id);

        if(empty($user))
        {
            return zcjy_callback_data('没有找到该用户',1);
        }
        return zcjy_callback_data($user->is_login);
    }

    public function publishNameVarify(Request $request)
    {
        return app('common')->publishNameVarify($request->all());
    }

    public function publishSign(Request $request)
    {
        return app('common')->publishSign($request->all());
    }

    //上传文件
    public function uploadFile(Request $request)
    {
        $file =  Input::file('file');
        return app('common')->uploadFiles($file);
    }

    //开始二维码扫码操作
    public function startErweimaScan(Request $request)
    {
        //session(['ip_scan'.$request->ip()=>'wait scan']);
        Cache::put('ip_scan'.$request->ip(), 'wait scan',1);

        $erweima_param = $request->root().'/weixin_auth?ip='.$request->ip();
       
        return zcjy_callback_data(app('common')->generateErweima($request,$erweima_param));
    }

    //询问二维码状态
    public function askScanErweimaResult(Request $request)
    {
        $scan_result = Cache::get('ip_scan'.$request->ip());

        if(is_numeric($scan_result)){
            $user = User::find($scan_result);
            if(!empty($user)){
                auth('web')->login($user);
                $this->updateUserInfo($user,$request);
                $scan_result = 'login success';
            }
        }
        return zcjy_callback_data($scan_result);
    }

    //下载图
    public function downloadImg(Request $request)
    {
        $input = $request->all();
        $rate = 60;
        if(array_key_exists('rate',$input) && !empty($input['rate']) && is_numeric($input['rate'])){
            $rate = $input['rate'];
        }
        return app('common')->downloadImage($request->get('url'),$rate);
    }
    

}