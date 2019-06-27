<?php

namespace App\Repositories;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use App\Repositories\CandidateRepository;
use App\Repositories\AwardCandidateRepository;

use Log;
use Overtrue\EasySms\EasySms;
use Image;
use Carbon\Carbon;
use App\User;
use Request;
use Yansongda\Pay\Pay;
use App\Models\VoteLog;
use Excel;


/**
 * Class ClientRepository
 * @package App\Repositories
 * @version December 26, 2017, 10:08 am CST
 *
 * @method Client findWithoutFail($id, $columns = ['*'])
 * @method Client find($id, $columns = ['*'])
 * @method Client first($columns = ['*'])
*/
class CommonRepository 
{
 
     private $CandidateRepository;
     private $AwardCandidateRepository;

     public function __construct(
       CandidateRepository $CandidateRepo,
       AwardCandidateRepository $AwardCandidateRepo
     ){
       $this->CandidateRepository = $CandidateRepo;
       $this->AwardCandidateRepository = $AwardCandidateRepo;
     }

    
    public function AwardCandidateRepo()
    {
      return $this->AwardCandidateRepository;
    }

    public function CandidateRepo()
    {
      return $this->CandidateRepository;
    }

    public function nowCanSign()
    {
      return Cache::get('sign_end_time');
    }

    public function setSignEndTime()
    {

      $min = empty(getSettingValueByKey('sign_validity')) ? 1 : getSettingValueByKey('sign_validity');
      Cache::put('sign_end_time',Carbon::now()->addMinutes($min)->addSeconds(5),$min);
    }

    public function setLogout($user_id)
    {
      $user = User::find($user_id);
      if(empty($user))
      {
        return;
      }
      $user->update(['is_login'=>0]);
    }

    /**
     * 发起姓名验证
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function publishNameVarify($input)
    {
      if(!isset($input['name']))
      {
        return zcjy_callback_data('请先输入姓名',1);
      }

      $user = User::where('name',$input['name'])->first();

      if(empty($user))
      {
        return zcjy_callback_data('您当前没有投票权限,谢谢合作!',1);
      }

      if($user->is_login)
      {
        return zcjy_callback_data('该姓名已被使用登录请更换姓名,谢谢合作!',1);
      }

      $user->update(['is_login'=>1]);

      return zcjy_callback_data($user->id);
    }

    /**
     * 发起投票
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function publishSign($input)
    {
      if(!isset($input['user_id']))
      {
         return zcjy_callback_data('投票人信息不完整',1);
      }

      if(!isset($input['award_id']))
      {
         return zcjy_callback_data('奖项信息不完整',1);
      }

      if(!isset($input['candidate_id']))
      {
         return zcjy_callback_data('请选择候选人',1);
      }

      $voteLog = VoteLog::where('user_id',$input['user_id'])
      ->where('award_id',$input['award_id'])
      ->first();

      if(!empty($voteLog))
      {
        return zcjy_callback_data('单轮奖项每人仅能投票一次',1);
      }

      if(!$this->nowCanSign()){
        return zcjy_callback_data('当前无法投票,请耐心等待活动进程',1);
      }

      //创建投票记录
      VoteLog::create([
        'user_id'      => $input['user_id'],
        'award_id'     => $input['award_id'],
        'candidate_id' => $input['candidate_id'],
      ]);

      $awardCandidate = $this->AwardCandidateRepository->getAwardCandidate($input['award_id'],$input['candidate_id']);

      #更新投票总数
      if(!empty($awardCandidate))
      {
        $awardCandidate->update(['num'=>$awardCandidate->num + 1]);
      }

      return zcjy_callback_data('发起投票成功');
    }



     public function autowrap($string,$fontsize=20, $angle=0, $width=760) {
          $fontface = public_path().'/fonts/XinH_CuJW.TTF';
          // 参数分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
          $content = "";
          // 将字符串拆分成一个个单字 保存到数组 letter 中
          preg_match_all("/./u", $string, $arr);
          $letter = $arr[0];
          foreach($letter as $l) {
              $teststr = $content.$l;
              $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
              if (($testbox[2] > $width) && ($content !== "")) {
                  $content .= PHP_EOL;
              }
              $content .= $l;
          }
          return $content;
      }

     /**
      * [生成快讯]
      * @param  [type] $post_id [description]
      * @return [type]          [description]
      */
     public function generateQuickNews($post,$request,$generate_repeat=true){
        #生成的相对路径
        $generate_path = '/images/quicknews/'.$post->id.'.jpg';

        if(!file_exists(public_path().$generate_path) || $generate_repeat){

          #底图http路径
          $base_image_path =  empty(getSettingValueByKeyCache('quick_news_base_img')) ? $request->root().'/images/quicknews/base.jpg' : getSettingValueByKeyCache('quick_news_base_img');

          #底图绝对文件路径
          $base_image_path =  public_path(parse_url($base_image_path)['path']);

          #打开底图
          $base_image =  Image::make($base_image_path);

          #插入利好 
          $base_image->text($post->like, 145, 375, function($font) {
              $font->file(public_path().'/fonts/XinH_CuJW.TTF');
              $font->size(26);
              $font->color('#111');
          });

           #插入利空
          $base_image->text($post->like, 455, 375, function($font) {
              $font->file(public_path().'/fonts/XinH_CuJW.TTF');
              $font->size(26);
              $font->color('#111');
          });

          #插入标题
          $title = $this->autowrap($post->name,20,0,786);
          $base_image->text($title, 35, 445, function($font) {
              $font->file(public_path().'/fonts/XinH_CuJW.TTF');
              $font->size(20);
              $font->color('#111');
              //fe5757
          });

          #插入日期时间
          $post_date = time_parse($post->created_at)->format('m月d日 H:i');
          $base_image->text($post_date, 35, 525, function($font) {
              $font->file(public_path().'/fonts/XinH_CuJW.TTF');
              $font->size(20);
              $font->color('#636e9b');
          });

         
          #插入正文
          $content = $this->autowrap($post->KuaiXunLimit);
          $base_image->text($content, 35, 575, function($font) {
              $font->file(public_path().'/fonts/XinH_CuJW.TTF');
              $font->size(20);
              $font->color('#111');
          });

          #插入底部名称
          $name = getSettingValueByKeyCache('name');
          $base_image->text($name, 155, 1090, function($font) {
              $font->file(public_path().'/fonts/XinH_CuJW.TTF');
              $font->size(24);
              $font->color('#636e9b');
          });


          #插入公众号二维码
          $qrcode_url = empty(getSettingValueByKeyCache('weixin')) ? $request->root().'/images/erweima.png' : getSettingValueByKeyCache('weixin');

          $qrcode_base_path =  public_path(parse_url($qrcode_url)['path']);


          $qrcode = Image::make($qrcode_base_path)->resize(108, 108);

          #插入二维码
          $base_image->insert($qrcode, 'bottom-right', 60, 90);

          #保存图片
          $base_image->save(public_path().$generate_path,90);
        }

        return $request->root().$generate_path;
     }

     /**
      * 浏览器中直接打开图片
      * @param  [type] $path [description]
      * @return [type]       [description]
      */
     public function openImg($path)
     {
        $img = file_get_contents($path,true);
        header("Content-Type: image/jpeg;text/html; charset=utf-8");
        echo $img;
        exit;
     }

    /**
     * [下载处理压缩图]
     * @param  [type]  $url  [description]
     * @param  integer $rate [description]
     * @param  string  $path [description]
     * @return [type]        [description]
     */
    public function downloadImage($url,$rate=60,$path='download/')
    {
        #发起图片资源curl请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        #能请求到文件
        if($file)
        {
          #基本地址
          $path = public_path($path);
          #本地存储
          $filename = $this->saveAsImage($url, $file, $path);
          #拼接路径
          $img_path = $path.$filename;
          #新实例化图
          $img = Image::make($img_path);
          #压缩品质
          $img->save($img_path, $rate);
          #浏览器打开
          return $this->openImg(Request::root().'/download/'.$filename);
        }
     }
    
    /**
     * [保存图片]
     * @param  [type] $url  [description]
     * @param  [type] $file [description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    private function saveAsImage($url, $file, $path)
    {
      $filename = pathinfo($url, PATHINFO_BASENAME);
      if(file_exists($path.$filename)){
        unlink($path.$filename);
      }
      $resource = fopen($path . $filename, 'a');
      fwrite($resource, $file);
      fclose($resource);
      return $filename;
    }

     public function generateErweima($request,$param,$size=200){
         $qr_code_path = public_path('qrcodes/'.zcjy_base64_en($param).'.jpg');
         if(!file_exists($qr_code_path)){
              \QrCode::format('png')->size($size)->generate($param,$qr_code_path);
              $qrcode = Image::make($qr_code_path);
              $qrcode->save($qr_code_path, 60);

         }
         return $request->root().'/qrcodes/'.zcjy_base64_en($param).'.jpg';
     }


/**
 * [上传文件]
 * @param  [type] $file     [description]
 * @param  string $api_type [description]
 * @param  [type] $user     [description]
 * @return [type]           [description]
 */
function uploadFiles($file , $api_type = 'web' , $user = null,$insert_shuiyin=false){
        if(empty($file)){
            return zcjy_callback_data('文件不能为空',1,$api_type);
        }
        #文件类型
        $file_type = 'file';
        #文件实际后缀
        $file_suffix = $file->getClientOriginalExtension();
        if(!empty($file)) {
              #图片
              $img_extensions = ["png", "jpg", "gif","jpeg"];
              #音频
              $sound_extensions = ["PCM","WAVE","MP3","OGG","MPC","mp3PRo","WMA","wma","RA","rm","APE","AAC","VQF","LPCM","M4A","cda","wav","mid","flac","au","aiff","ape","mod","mp3"];
              #excel
              $excel_extensions = ["xls","xlsx","xlsm"];
              #word pdf ppt
              $word_extensions = ["PDF","pdf","doc","docx","ppt","pptx","pps","ppsx","pot","ppa"];
              if ($file_suffix && !in_array($file_suffix , $img_extensions) && !in_array($file_suffix , $sound_extensions) && !in_array($file_suffix,$excel_extensions)) {
                 // return zcjy_callback_data('上传文件格式不正确',1,$api_type);
              }
              if(in_array($file_suffix, $img_extensions)){
                  $file_type = 'image';
              }
              if(in_array($file_suffix, $sound_extensions)){
                  $file_type = 'sound';
              }
              if(in_array($file_suffix,$excel_extensions)){
                  $file_type = 'excel';
              }
              if(in_array($file_suffix,$word_extensions)){
                  $file_type = 'word';
              }
        }

        #文件夹
        $destinationPath = empty($user) ? "uploads/admin/" : "uploads/user/".$user->id.'/';
        #加上类型
        $destinationPath = $destinationPath.$file_type.'/';

        if (!file_exists($destinationPath)){
            mkdir($destinationPath,0777,true);
        }
       
        $extension = $file_suffix;
        $fileName = str_random(10).'.'.$extension;
        $file->move($destinationPath, $fileName);

        #对于图片文件处理
        if($file_type == 'image'){
          $image_path=public_path().'/'.$destinationPath.$fileName;
          $img = Image::make($image_path);
         // 插入水印, 水印位置在原图片的右下角, 距离下边距 10 像素, 距离右边距 15 像素
          //$img->insert(public_path().'/images/gol/water1.png', 'bottom-right', 15, 15);
         //$img->resize(640, 640);
         $img->save($image_path,70);

        }

        $host='http://'.$_SERVER["HTTP_HOST"];

        if(env('online_version') == 'https'){
             $host='https://'.$_SERVER["HTTP_HOST"];
        }

        #路径
        $path=$host.'/'.$destinationPath.$fileName;

        return zcjy_callback_data([
                'src'=>$path,
                'current_time' => Carbon::now()->format('Y-m-d H:i:s'),
                'type' => $file_type,
                'current_src' => public_path().'/'.$destinationPath.$fileName
            ],0,$api_type);
    }

    //发送短信验证码
    public function sendVerifyCode($mobile,$type='reg')
    {
      $code = rand(100000,999999);
      sendSMS($mobile,$code);
        // $config = [
        //     // HTTP 请求的超时时间（秒）
        //     'timeout' => 5.0,

        //     // 默认发送配置
        //     'default' => [
        //         // 网关调用策略，默认：顺序调用
        //         'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        //         // 默认可用的发送网关
        //         'gateways' => [
        //             'aliyun',
        //         ],
        //     ],
        //     // 可用的网关配置
        //     'gateways' => [
        //         'errorlog' => [
        //             'file' => '/tmp/easy-sms.log',
        //         ],
        //         'aliyun' => [
        //             'access_key_id' => Config::get('web.SMS_ID'),
        //             'access_key_secret' => Config::get('web.SMS_KEY'),
        //             'sign_name' => Config::get('web.SMS_SIGN'),
        //         ]
        //     ],
        // ];

        // $easySms = new EasySms($config);

        // $code = rand(1000,9999);

        // $easySms->send($mobile, [
        //     'content'  => '短信验证码:'.$code,
        //     'template' => Config::get('web.SMS_TEMPLATE_VERIFY'),
        //     'data' => [
        //         'code'=>$code
        //     ],
        // ]); 
        session(['mobile_code_'.$type.$mobile=>$code]);
        return $code;   
    }
    
    /**
     * [用户的认证状态]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function authCert($user)
    {
        return  $user ? $user->cert()->orderBy('created_at','desc')->first() : null;
    }

    /**
     * [检查认证]
     * @param  [type] $user        [description]
     * @param  string $attach_word [description]
     * @param  string $api_type    [description]
     * @return [type]              [description]
     */
    public function varifyCert($user,$attach_word='您当前',$api_type="web"){
        if(empty($user)){
            return zcjy_callback_data('未知错误',1,$api_type);
        }
        $status = false;
        $cert = $this->authCert($user);
        if(empty($cert)){
            return zcjy_callback_data($attach_word.'未认证,请在个人中心完成身份认证后使用',1,$api_type);
        }

        if($cert->status == '审核中' || $cert->status =='未通过'){
            return zcjy_callback_data($attach_word.'认证正在审核中或未通过审核',1,$api_type);
        }

        // if($attach_word == 'result'){
        //     return $cert;
        // }

        return $status;
    }

     //读取excel文件
    public function loadExcels($files){
       if (!file_exists($files)){
          //return zcjy_callback_data('没有找到该文件',1);
          return false;
       }
       $res = [];
       Excel::load($files, function($reader) use( &$res ) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
       }); 
       return $res;
    }

    /**
     * [上传文件并且自动生成用户信息]
     * @param  [type] $files [description]
     * @return [type]        [description]
     */
    public function readExcelsToGenerateUser()
    {
       #先清空
       User::orderBy('created_at','asc')->delete();
       $files = public_path('now.xlsx');
       $res= $this->loadExcels($files);
       if(count($res) > 1){

            $add_users_num = 0;
            $false_users_num = 0;
            $users_arr = [];
            for ($i=1; $i < count($res); $i++) 
            { 
                      
                      // User::create([
                      //   'name'       => $res[$i][3],
                      // ]);  
                      if(isset($res[$i][4]) && !is_numeric($res[$i][4]))
                      {
                        $users_arr[] = $res[$i][4];
                      }    
            }
            $users_arr = array_unique($users_arr);
            foreach ($users_arr as $key => $name) {
              $add_users_num ++;
                 User::create(
                  [
                        'name'       => $name,
                      ]
                  ); 
            }
           
            dd($users_arr);
            dd('上传成功,此次新增'.$add_users_num.'个新用户,'.$false_users_num.'个用户上传失败',0,'web');
       }
       else{
        return zcjy_callback_data('excel中无内容',1,'web');
       }
    }

}
