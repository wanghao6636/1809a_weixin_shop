<?php
namespace App\Http\Controllers\Weixin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
class jssdkController extends Controller{
    public function jsTest()
    {
        //计算签名
        $nonceStr=Str::random(10);
      $ticket=getJsapiTicket();
      // $aa=getJsapiTicket();
        //var_dump($aa);exit;
        $timestamp=time();
       // var_dump($_SERVER['REQUEST_URL']);exit;
        $current_url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //var_dump($current_url);exit;


        $string1="jsapi_ticket=$ticket&nonceStr=$nonceStr&timestamp=$timestamp&url=$current_url";
        var_dump($string1);exit;
        $sign=sha1($string1);



        $appid='wxf45738393e3e870a';

        $js_config=[
            'appId'=> $appid, // 必填，公众号的唯一标识
            'timestamp'=> $timestamp, // 必填，生成签名的时间戳
            'nonceStr'=>$nonceStr, // 必填，生成签名的随机串
            'signature'=>$sign,// 必填，签名
        ];
        //var_dump($js_config);exit;
        $data=[
            'js_config'=>$js_config // 必填，需要使用的JS接口列表
        ];
        return view('weixin.jssdk',$data);
    }
    public function getImg()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }
}