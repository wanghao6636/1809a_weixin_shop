<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Model\OrderModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
class ZffController extends Controller
{
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

    //任务周期
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
//        $Appid="wxf45738393e3e870a";
//      //  var_dump($Appid);exit;
//        $Secret="04c57ee962b7bf78d85050ce9d213833";
       // var_dump($Secret);exit;
        $drr="http://1809wanghao.comcto.com/ino";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WX_APP_ID').'&redirect_uri=$drr&response_type=code&scope=snsapi_userinfo&state=a-z#wechat_redirect";
        return view('weixin.wechat',['url'=>$url]);
    }

    public function ino(Request $request){
        $access = $this->getaccessToken();
        //var_dump($access);exit;
        $arr = $request->input();
        //var_dump($arr);exit;

        $code = $arr['code'];
//        $appid = "wxf45738393e3e870a";
//        $appkey = "04c57ee962b7bf78d85050ce9d213833";
        $accessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APP_ID').'&secret='.env('WX_APP_SEC').'&code=$code&grant_type=authorization_code";
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

        $res = DB::table('wechat')->where('openid',$openid)->first();
        if(empty($res)){
            DB::table('weixin_wechat')->insert($wechatdata);
            echo "<h5>授权成功</h5>";
        }else{
            echo "欢迎回来";
        }
    }



    public function xmladd(Request $request)
    {

        $client = new Client();
        //var_dump($client);exit;
        //echo $request->input('echostr');
        $str = file_get_contents("php://input");
        //var_dump($str);exit;
        $objxml = simplexml_load_string($str);
        //var_dump($objxml);exit;
        file_put_contents("/tmp/1809a_weixin.log", $str, FILE_APPEND);


        $Event = $objxml->Event;
        $FromUserName = $objxml->FromUserName;
        $ToUserName = $objxml->ToUserName;
        $MsgType = $objxml->MsgType;
        $MediaId = $objxml->MediaId;
        $Content = $objxml->Content;

        $access = $this->getaccessToken();
        $userUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$FromUserName&lang=zh_CN";
        $userAccessInfo = file_get_contents($userUrl);
        $userInfo = json_decode($userAccessInfo, true);
        //var_dump($userInfo);exit;
        $name = $userInfo['nickname'];
        $sex = $userInfo['sex'];
        $headimgurl = $userInfo['headimgurl'];
        $openid1 = $userInfo['openid'];
        if ($Event == 'subscribe') {
            $data = DB::table('kaoshi')->where('openid', $FromUserName)->count();
            //print_r($data);die;
            if ($data == '0') {
                $weiInfo = [
                    'name' => $name,
                    'sex' => $sex,
                    'img' => $headimgurl,
                    'openid' => $openid1,
                    'time' => time()
                ];
                DB::table('kaoshi')->insert($weiInfo);

                //回复消息
                $time = time();
                $content = "关注本公众号成功";
                $xmlStr = "
                   <xml>
                        <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                        <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                        <CreateTime>$time</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[$content]]></Content>
                   </xml>";
                echo $xmlStr;

            }else{
                $time = time();
                $content = "欢迎" . $name . "回来";
                $xmlStr = "
                   <xml>
                        <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                        <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                        <CreateTime>$time</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[$content]]></Content>
                   </xml>";
                echo $xmlStr;
            }

        }



        if($MsgType=='text'){
            if($Content == '最新商品'){
                $goodsHotInfo=DB::table('weixin_goods')->orderBy('create_time','desc')->limit(5)->get(['goods_id','goods_name','goods_img','goods_selfprice'])->toArray();
                $goods_id = $goodsHotInfo[0]->goods_id;
                $ToUserName = $FromUserName;
                $FormUserName = "gh_cf7ceceb3c6e";
                $CreateTime = time();
                $MsgType = 'news';
                $ArticleCount = 1;
                $Titkle = '最新消息';
                $Description = '最新商品信息';
                $PicUrl = 'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=4224459274,772817564&fm=27&gp=0.jpg';
                $Url = 'http://1809wanghao.comcto.com/jsdemo?goods='.$goods_id;

                $response_xml = "
                <xml>
                     <ToUserName><![CDATA[$ToUserName]]></ToUserName>
                     <FromUserName><![CDATA[$FormUserName]]></FromUserName>
                     <CreateTime>$CreateTime</CreateTime>
                     <MsgType><![CDATA[$MsgType]]></MsgType>
                     <ArticleCount>$ArticleCount</ArticleCount>
                     <Articles>
                          <item>
                               <Title><![CDATA[$Titkle]]></Title>
                               <Description><![CDATA[$Description]]></Description>
                               <PicUrl><![CDATA[$PicUrl]]></PicUrl>
                               <Url><![CDATA[$Url]]></Url>
                          </item>
                     </Articles>
                </xml>
                ";
                echo $response_xml;
            }


        }else if($MsgType=='image') {
            $access = $this->getaccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access&media_id=$MediaId";
            $response = $client->get(new Uri($url));
            $headers = $response->getHeaders();
            $file_info = $headers['Content-disposition'][0];
            $file_name = rtrim(substr($file_info, -20), '"');
            $new_file_name = "/tmp/image/" . date("Y-m-d H:i:s") . $file_name;

            $rs = Storage::put($new_file_name, $response->getBody());
            //print_r($rs);exit;
//            $time = time();
//            $res_str = file_get_contents($url);
//
//            file_put_contents("/tmp/image/$time.jpg", $res_str, FILE_APPEND);
            if ($rs == '1') {
                //echo '1111';exit;
                $dataInfo = [
                    "nickname" => $userInfo['nickname'],
                    "openid" => $openid1,
                    "img" => $new_file_name
                ];
                //var_dump($dataInfo);exit;
                $imginfo = DB::table('image')->insert($dataInfo);

            }
        }else if ($MsgType == 'voice') {
            $access = $this->getaccessToken();
            $vourl = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access&media_id=$MediaId";
            $response = $client->get(new Uri($vourl));
            $headers = $response->getHeaders();
            $voice_info = $headers['Content-disposition'][0];
            $voice_name = rtrim(substr($voice_info, -20), '"');
            $new_voice_name = "/tmp/voice/" . date("Y-m-d H:i:s") . $voice_name;

            $vors = Storage::put($new_voice_name, $response->getBody());
            //print_r($vors);exit;
//            $time = time();
//            $res_str = file_get_contents($url);
//
//            file_put_contents("/tmp/image/$time.jpg", $res_str, FILE_APPEND);
            if ($vors == '1') {
                //echo '1111';exit;
                $dataInfo = [
                    "nickname" => $userInfo['nickname'],
                    "openid" => $openid1,
                    "voice" => $new_voice_name
                ];
                //var_dump($dataInfo);exit;
                $imginfo = DB::table('voice')->insert($dataInfo);
            }
        }
    }





    //微信分类
    public function createadd(Request $request){
        $access = $this->getaccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access";
        $arr = array(
            'button'=>array(
                array(
                    "name"=>"︿(￣︶￣)︿",
                    "type"=>"click",
                    "key"=>"aaa",
                    "sub_button"=>array(
                        array(
                            "type"=>"pic_weixin",
                            "name"=>"发送图片",
                            "key"=>"aaa",
                        ),
                    ),
                ),
                array(
                    "name"=>"搞笑",
                    "type"=>"view",
                    "url"=>"https://img04.sogoucdn.com/app/a/100520024/ee89b873bc8f90af0f7c6de5d9bcade4"
                ),
            ),
        );

        $strJson = json_encode($arr,JSON_UNESCAPED_UNICODE);
       // var_dump($strJson);exit;
        $objurl = new Client();
        $response = $objurl->request('POST',$url,[
            'body' => $strJson
        ]);
        
        $res_str = $response->getBody();
        //var_dump($res_str);
        return $res_str;
    }


}