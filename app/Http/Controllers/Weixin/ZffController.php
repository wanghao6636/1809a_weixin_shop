<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Model\OrderModel;
use App\Model\GoodsModel;
use App\Model\StacModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
class ZffController extends Controller
{

    public function valid()
    {
        echo $_GET['echostr'];
    }

    public function wxEvent()
    {
        $xml_str=file_get_contents("php://input");
        //var_dump($xml_str);exit;
        $log_str=date('Y-m-d H:i:s');
        $str=$xml_str.$log_str."\n";
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);
        //var_dump($str);exit;
        $xml_obj=simplexml_load_string($xml_str);
        //var_dump($xml_obj);exit;
        $open_id=$xml_obj;
        //var_dump($open_id);exit;
    }
    //
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

    public  function secod(Request $request)
    {
        $access=$this->getaccessToken();
        //var_dump($access);exit;
        $arr=$request->input();
        //var_dump($arr);exit;
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access";
       // var_dump($url);exit;
        $data=[
            'expire_seconds'=>2592000,
            'action_name'=>"QR_STR_SCENE",
            'action_info'=>[
                    'scene'=>[
                        'scene_id'=>1,
                    ],
            ]
        ];
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $Client=new Client();
        $response = $Client->request('POST',$url,[
            'body' => $json
        ]);
        $res_str = $response->getBody();
        //var_dump($res_str);exit;
        $ass = json_decode($res_str,true);
        //var_dump($ass);exit;
        $ticket=$ass['ticket'];
        //var_dump($ticket);exit;
        $tkurl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
        //var_dump($tkurl);exit;
        $list=GoodsModel::get()->toArray();
        //ar_dump($list);exit;
//        $data=[
//            'list'=>$list,
//        ];
       //var_dump($data);exit;

        return view('weixin.secod',['tkurl'=>$tkurl,'list'=>$list]);
    }


    public function index()
    {
        $list=GoodsModel::get()->toArray();
        //ar_dump($list);exit;
        $data=[
            'list'=>$list,
        ];
       // var_dump($data);exit;
        return view('weixin.secod',$data);
    }

    //公众号搜索商品，图文serch
    public  function serch()
    {

    }


//    public function key(Request $request)
//    {
//        $input=$request->all();
//        //echo 111;exit;
//        //var_dump($id);exit;
//        $id = intval($_GET['id']);
//        $key = $id;
////       print_r($key);die;
//        $redis_view='ss:goods:view';
////        print_r($redis_view);die;
//        $history = Redis::incr($key);
//        echo $history;
//        Redis::zAdd($redis_view,$history,$id);
//        $res = GoodsModel::where(['id'=>$id])->first()->toArray();
////        print_r($res);die;
//        $goods_data = [
//            'key'=>$key
//        ];
////        print_r($goods_data);die;
//        if($res){
//            GoodsModel::where(['id'=>$id])->update($goods_data);
//        }else{
//            $detail = [
//                'id'=> $id,
//                'name'=> $res ->name,
//                'price'=>$res->price,
//                'num'=>$res->num,
//                'key_id'=> $res['key_id'] +1
//            ];
//            GoodsModel::insertGetId($detail);
//        }
//        $data = [
//            'res' => $res
//        ];
//        $list=Redis::zRangeByScore($redis_view,0,10000,['Withscores'=>true]);
//        $lists=Redis::zRevRange($redis_view,0,1000,true);
//        $info=[];
//        foreach ($lists as $k=>$v){
//            $info[]=GoodsModel::where(['id'=>$k])->first()->toArray();
//        }
//        return view('weixin.secod',$data,['info'=>$info]);
//
//    }


    //
//    public function wxEvent()
//    {
//        $xml_str = file_get_contents("php://input");
//        //var_dump($xml_str);exit;
//        $log_str = date('Y-m-d H:i:s'). ':' .$xml_str ."\n";
//        //var_dump($log_str);exit;
//        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
//        $xml_obj =simplexml_load_string($xml_str);
//        //var_dump($xml_obj);exit;
//        $msg_type =$this->MsgType;          //消息
//        $open_id = $xml_obj->FromUserName;      //openid
//        $app = $xml_obj->ToUserName;            // 公众ID
//        if($open_id=='text'){          //文本消息
//            if(strpos($xml_obj->Content,'最新商品') !== false ){
//                 $response_xml = '<xml>
//                      <ToUserName><![CDATA['.$open_id.']]></ToUserName>
//                      <FromUserName><![CDATA['.$app.']]></FromUserName>
//                      <CreateTime>'.time().'</CreateTime>
//                      <MsgType><![CDATA[news]]></MsgType>
//                      <ArticleCount>1</ArticleCount>
//                      <Articles>
//                        <item>
//                          <Title><![CDATA[最新商品]]></Title>
//                          <Description><![CDATA[IPhoneX]]></Description>
//                          <PicUrl><![CDATA[http://pic.sogou.com/pics/recompic/detail.jsp?category=%E6%90%9E%E7%AC%91&tag=%E6%90%9E%E7%AC%91%E4%BA%BA%E7%89%A9#14%26487681]]></PicUrl>
//                          <Url><![CDATA[http://pic.sogou.com/pics/recompic/detail.jsp?category=%E6%90%9E%E7%AC%91&tag=%E6%90%9E%E7%AC%91%E4%BA%BA%E7%89%A9#23%26497092]]></Url>
//                        </item>
//                      </Articles>
//                    </xml>';
//            }http:
//        }
//        echo $response_xml;
//    }

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
        $Appid="wxf45738393e3e870a";
//      //  var_dump($Appid);exit;
       // $Secret="04c57ee962b7bf78d85050ce9d213833";
       // var_dump($Secret);exit;
        $drr="http://1809wanghao.comcto.com/ino";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$Appid&redirect_uri=$drr&response_type=code&scope=snsapi_userinfo&state=a-z#wechat_redirect";
        //var_dump($url);exit;
        return view('weixin.wechat',['url'=>$url]);
    }

    public function ino(Request $request){

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




    //微信菜单分类
    public function createadd(Request $request){
        $access = $this->getaccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access";
        $arr = array(
            'button'=>array(
                array(
                    "name"=>"最新福利",
                    "type"=>"click",
                    "key"=>"aaa",
                    "url"=>"http://pic.sogou.com/pics/recompic/detail.jsp?category=%E6%90%9E%E7%AC%91&tag=%E6%90%9E%E7%AC%91%E4%BA%BA%E7%89%A9#12%26487681",

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









    public function wechat(){
        $url = urlencode("http://1809zhanghaowei.comcto.com//wechatToken");
//        $appid = "wx51db63563c238547";
        $scope = "snsapi_userinfo";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WX_APP_ID')."&redirect_uri=$url&response_type=code&scope=$scope&state=STATE#wechat_redirect";
        return view('weixin.wechat',['url'=>$url]);
    }
    public function wechatToken(Request $request){
        $access = $this->accessToken();
        $arr = $request->input();
        //var_dump($arr);exit;
        $code = $arr['code'];
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