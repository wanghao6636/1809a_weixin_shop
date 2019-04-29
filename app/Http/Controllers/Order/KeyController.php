<?php
namespace App\Http\Controllers\Order;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
class KeyController extends Controller
{
    public function index()
    {
        $list=GoodsModel::get()->toArray();
        //ar_dump($list);exit;
        $data=[
            'list'=>$list,
            ];
        //var_dump($data);exit;
        return view('key.key',$data);
    }

    public function key(Request $request)
    {
        $input=$request->all();
        //echo 111;exit;
        //var_dump($id);exit;
        $id = intval($_GET['id']);
        $key = $id;
//       print_r($key);die;
        $redis_view='ss:goods:view';
//        print_r($redis_view);die;
        $history = Redis::incr($key);
        echo $history;
        Redis::zAdd($redis_view,$history,$id);
        $res = GoodsModel::where(['id'=>$id])->first()->toArray();
//        print_r($res);die;
        $goods_data = [
            'key'=>$key
        ];
//        print_r($goods_data);die;
        if($res){
           GoodsModel::where(['id'=>$id])->update($goods_data);
        }else{
            $detail = [
                'id'=> $id,
                'name'=> $res ->name,
                'price'=>$res->price,
                'num'=>$res->num,
                'key_id'=> $res['key_id'] +1
            ];
            GoodsModel::insertGetId($detail);
        }
        $data = [
            'res' => $res
        ];
        $list=Redis::zRangeByScore($redis_view,0,10000,['Withscores'=>true]);
        $lists=Redis::zRevRange($redis_view,0,1000,true);
        $info=[];
        foreach ($lists as $k=>$v){
            $info[]=GoodsModel::where(['id'=>$k])->first()->toArray();
        }
        return view('key.key',$data,['info'=>$info]);

    }
//    public function getSort()
//    {
//        $key='ss:goods_id:view';
//        //var_dump($key);exit;
//        $list1=Redis::zRangeByScore($key,0,10000,['withscores'=>true]);
//        //var_dump($list1);exit;
//        //echo $list1;exit;
//        $list2=Redis::zRevRange($key,0,10000,ture);
//        $kee='goods_name: 666';
//        // Redis ::zAdd('kee' ,'6','val6');
//      // var_dump($kee);exit;
//    }
//    //用哈希方法
//    public  function cacheGoods($goods_id){
//        $goods_id=intval($goods_id);
//        var_dump($goods_id);exit;
//        $redis_cache_goods_key='h:goods_info:'.$goods_id;
//        //判断是否有缓存
//        if(!cache_info){
//                                    //有缓存
//        }else{
//              $goods_info=GoodsModel::where(['id'=>$goods_id])->first()->toArray();
//              Redis::hMset($redis_cache_goods_key,$goods_info);
//
//              //没缓存
//        }
//    }
}




