<?php
namespace App\Http\Controllers\Weixin;
use App\Model\OrderModel;
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

    public  function wechat(request $request)
    {
        $Appid="wxf45738393e3e870a";
//      //  var_dump($Appid);exit;
        // $Secret="04c57ee962b7bf78d85050ce9d213833";
        // var_dump($Secret);exit;
        $drr="http://1809wanghao.comcto.com/ino";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$Appid&redirect_uri=$drr&response_type=code&scope=snsapi_userinfo&state=a-z#wechat_redirect";
        //var_dump($url);exit;
        return view('weixin.wechat',['url'=>$url]);
    }

    public function wechatToken(Request $request){

        $access = $this->getaccessToken();
        //var_dump($access);exit;
        $arr = $request->input();
        // var_dump($arr);exit;
        // echo 1111;exit;
        $code = $arr['code'];
        //var_dump($code);exit;
        $appid = "wxf45738393e3e870a";
        $appkey = "04c57ee962b7bf78d85050ce9d213833";
        $accessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appkey&code=$code&grant_type=authorization_code";
        $info = file_get_contents($accessToken);
        $arr = json_decode($info,true);
        //var_dump($arr);exit;
        $openid = $arr['openid'];
        //var_dump($openid);exit;
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

        $res = DB::table('weixin_wechat')->where('openid',$openid)->first();
        //var_dump($res);exit;
        if(empty($res)){
            DB::table('weixin_wechat')->insert($wechatdata);
            echo "<h5>授权成功</h5>";
        }else{
            echo "欢迎回来";
        }
    }
    //创建用户标签
    public function test(Request $request){
        $access=$this->getaccessToken();
        //var_dump($access);exit;
        $arr=$request->input();
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=$access";
        $data=[
            'access_token'=>$access,
            'name'=>"月考呐",
        ];
       //var_dump($data);exit;
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        $Client=new Client();
        //var_dump($json);exit;
        $response = $Client->request('POST',$url,[
            'body' => $json
        ]);

        $res_str = $response->getBody();
        $ass = json_decode($res_str,true);
        //var_dump($ass);exit;

    }
    //定时任务
    public function del(){
        // echo 2;'</hr>';
        $arr=OrderModel::all()->toArray();
        //var_dump($arr);exit;
        // echo'<pre>';print_r($res);echo'</pre>';
        foreach($arr as $k=>$v){
            if(time()-$v['add_time']>1800&& $v['pay_time']==0){
                $res=OrderModel::where(['oid'=>$v['oid']])->update(['is_up'=>1]);
                //var_dump($res);exit;
            }
        }
    }
    //群发
    public function openiddo(Request $request){
        $objurl = new Client();
        $access = $this->getaccessToken();
        //获取测试号下所有用户的openid
        $userurl = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access";
        $info = file_get_contents($userurl);
        $arrInfo = json_decode($info, true);
        //var_dump($arrInfo);exit;
        $data = $arrInfo['data'];
        $openid = $data['openid'];
        //调用接口根据openid群发
        $msgurl = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$access";
        $content = "欢迎新用户";
        $arr = array(
            'touser'=>$openid,
            'msgtype'=>"text",
            'text'=>[
                'content'=>$content,
            ],
        );
        //print_r($arr);
        $strjson = json_encode($arr,JSON_UNESCAPED_UNICODE);
        $objurl = new Client();
        $response = $objurl->request('POST',$msgurl,[
            'body' => $strjson
        ]);
        $res_str = $response->getBody();
        //var_dump($res_str);
        return $res_str;
    }


}