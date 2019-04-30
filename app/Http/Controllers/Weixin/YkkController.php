<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
use App\Model\StacModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
class YkkController extends Controller
{
    //获取accesstoken
    function getaccessToken()
    {
        //Cache::pull('access');exit;
        $access = Cache('access');
        if (empty($access)){
            $appid = "wxf45738393e3e870a";
            $appkey = "04c57ee962b7bf78d85050ce9d213833";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appkey";
            $info = file_get_contents($url);
            $arrInfo = json_decode($info, true);
            // var_dump($arrInfo);exit;
            $key = "access";
            $access = $arrInfo['access_token'];
            //var_dump($access);exit;
            $time = $arrInfo['expires_in'];

            cache([$key => $access], $time);
        }
        return $access;
    }

    //微信网页授权登录
    public function wechat(){
        $url = urlencode("http://1809wanghao.comcto.com//wechatToken");
        $appid = "wxf45738393e3e870a";
        $scope = "snsapi_userinfo";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url&response_type=code&scope=$scope&state=STATE#wechat_redirect";
        return view('weixin.wechat',['url'=>$url]);
    }
    //微信授权回调
    public function wechatToken(Request $request){
        $access = $this->getaccessToken();
        $arr = $request->input();
        //var_dump($arr);exit;
        $code=$_GET("code");
        var_dump($code);exit;
//        $code = $arr['code'];
        $user_id = '15';
//        $appid = "wx51db63563c238547";
//        $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
        $accessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APP_ID')."&secret=".env('WX_KEY')."&code=$code&grant_type=authorization_code";
        $info = file_get_contents($accessToken);
        $arr = json_decode($info,true);
        //var_dump($arr);exit;
        $openid = $arr['openid'];
        $userUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$openid&lang=zh_CN";
        $userAccessInfo = file_get_contents($userUrl);
        $userInfo = json_decode($userAccessInfo, true);
        //var_dump($userInfo);exit;
        $name = $userInfo['nickname'];
        $sex = $userInfo['sex'];
        $headimgurl = $userInfo['headimgurl'];
        $updatedata = [
            'openid'=>$openid
        ];
        $wechatdata = [
            'user_name'=>$name,
            'user_sex'=>$sex,
            'headimgurl'=>$headimgurl,
            'openid'=>$openid
        ];
        $res = DB::table('wechat')->where('openid',$openid)->first();
        if(empty($res)){
            DB::table('wechat')->insert($wechatdata);
            DB::table('user')->where('user_id',$user_id)->update($updatedata);
            echo "授权成功";
        }else{
            echo "欢迎回来";
        }
    }


}