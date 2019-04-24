<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Model\OrderModel;
use App\Http\Controllers\Controller;
class ZffController extends Controller
{
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
        //echo 111;exit;
        //echo print_($_GET);exit;
        //$arr=$request ->input();
       // $code=$request['code'];
       // var_dump($code);exit;
        //获取accesstoken
        $code = $_GET['code'];
        $Appid="wxf45738393e3e870a";
      //  var_dump($Appid);exit;
        $Secret="04c57ee962b7bf78d85050ce9d213833";
       // var_dump($Secret);exit;
        $url='http://api.wenxin.qq.com/sns/oauth2/access_token?appid=.$Appid.&secret=.$Secret.&code=.$code.&response_type=authorization_code';
            //var_dump($url);exit;
        $response = json_decode(file_get_contents($url),true);
        $access_token = $response['access_token'];
        $openid = $response['openid'];
        //获取用户信心
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_info = json_decode(file_get_contents($url),true);
    }

}