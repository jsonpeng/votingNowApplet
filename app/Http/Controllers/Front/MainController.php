<?php
namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MainController extends Controller
{

    //首页
    public function index(Request $request)
    { 
        $data = $this->systemData();
        $error = $data['error'];
        $award = $data['award'];
        $candidates = $data['candidates'];
        return view('front.index',compact('error','award','candidates'));
    }

    private function systemData()
    {
        $info = app('common')->AwardCandidateRepo()->getNowAward();

        $error = null;
        $award = null;
        $candidates = [];
        if(!isset($info['award']))
        {
          $error = $info;
        }
        else
        {
          $award = $info['award'];
          $candidates = $info['candidates'];
        }
        return ['error'=> $error,'award'=> $award ,'candidates'=>$candidates];
    }

    public function ajaxChart(Request $request)
    {
      $data = $this->systemData();
      if(!isset($data['award']))
      {
        return zcjy_callback_data($data['error'],3);
      }
      return zcjy_callback_data($data);
    }

    public function chart(Request $request)
    {
        $data = $this->systemData();
        $error = $data['error'];
        $award = $data['award'];
        return view('front.chart',compact('error','award'));
    }

    //处理支付
    public function settle(Request $request)
    { 
          $ip = $request->ip();
          $price == 0;
          return view('front.settle',compact('price','items'));
    }

    //服务协议
    public function protocol(Request $request)
    {
      return view('front.protocol');
    }
    
}