<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Model\OrderModel;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
class ZffController extends Controller
{
    function getaccessToken()
    {
        //Cache::pull('access');exit;
        $access = Cache('access');
        if (empty($access)) {
            $appid = "wx51db63563c238547";
            $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appkey";
            $info = file_get_contents($url);
            $arrInfo = json_decode($info, true);
            $key = "access";
            $access = $arrInfo['access_token'];
            $time = $arrInfo['expires_in'];

            cache([$key => $access], $time);
        }
        return $access;
    }

    //
    public function wxEvent()
    {
        $xml_str = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . ' : ' . $xml_str . "\n";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
        $xml_obj = simplexml_load_string($xml_str);
        //print_r($xml_str);exit;
        $msg_type = $xml_obj->MsgType;          //消息
        $open_id = $xml_obj->FromUserName;      //openid
        $app = $xml_obj->ToUserName;            // 公众ID
        if($open_id=='text'){          //文本消息
            if(strpos($xml_obj->Content,'最新商品') !== false ){
                 $response_xml = '<xml>
                      <ToUserName><![CDATA['.$open_id.']]></ToUserName>
                      <FromUserName><![CDATA['.$app.']]></FromUserName>
                      <CreateTime>'.time().'</CreateTime>
                      <MsgType><![CDATA[news]]></MsgType>
                      <ArticleCount>1</ArticleCount>
                      <Articles>
                        <item>
                          <Title><![CDATA[最新商品]]></Title>
                          <Description><![CDATA[IPhoneX]]></Description>
                          <PicUrl><![CDATA[http://pic.sogou.com/pics/recompic/detail.jsp?category=%E6%90%9E%E7%AC%91&tag=%E6%90%9E%E7%AC%91%E4%BA%BA%E7%89%A9#14%26487681]]></PicUrl>
                          <Url><![CDATA[http://pic.sogou.com/pics/recompic/detail.jsp?category=%E6%90%9E%E7%AC%91&tag=%E6%90%9E%E7%AC%91%E4%BA%BA%E7%89%A9#23%26497092]]></Url>
                        </item>
                      </Articles>
                    </xml>';
            }http:
        }
        echo $response_xml;
    }


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

    public  function opp(request $request)
    {

        //$code = $_GET['code'];
        $Appid="wxf45738393e3e870a";
      //  var_dump($Appid);exit;
        $Secret="04c57ee962b7bf78d85050ce9d213833";
       // var_dump($Secret);exit;
        $drr="http://1809wanghao.comcto.com/ino";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$Appid&redirect_uri=$drr&response_type=code&scope=snsapi_userinfo&state=a-z#wechat_redirect";
        return view('weixin.wechat',['url'=>$url]);
    }
    public function ino(Request $request){
        $access = $this->getaccessToken();
        //var_dump($access);exit;
        $arr = $request->input();
        //var_dump($arr);exit;

        $code = $arr['code'];
        //$user_id = '15';
        $appid = "wxf45738393e3e870a";
        $appkey = "04c57ee962b7bf78d85050ce9d213833";
        $accessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appkey&code=$code&grant_type=authorization_code";
        $info = file_get_contents($accessToken);
        $arr = json_decode($info,true);
        //var_dump($arr);exit;
        $openid = $arr['openid'];
        $userUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$openid&lang=zh_CN";
        $userAccessInfo = file_get_contents($userUrl);
        $userInfo = json_decode($userAccessInfo, true);
        var_dump($userInfo);exit;
    }

}