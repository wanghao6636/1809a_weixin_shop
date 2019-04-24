<?php
namespace App\Http\Controllers\Weixin;
use Illuminate\Http\Request;
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
//        print_r($log_str);exit;
       // $msg_type = $xml_obj->MsgType;          //消息
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
}