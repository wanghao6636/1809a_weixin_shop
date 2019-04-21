<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\GoodsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Model\CartModel;
class CartController extends Controller
{
    /**
     * 购物车页面
     */
    public function index()
    {
        $cart_list = CartModel::where(['uid'=>Auth::id(),'session_id'=>Session::getId()])->get()->toArray();
        if($cart_list){
            $total_price = 0;
            foreach($cart_list as $k=>$v){
                $g = GoodsModel::where(['id'=>$v['goods_id']])->first()->toArray();
                $total_price += $g['price'];
                $goods_list[] = $g;
            }
            //展示购物车
            $data = [
                'goods_list' => $goods_list,
                'total'     => $total_price / 100
            ];
            return view('cart.index',$data);
        }else{
            header('Refresh:3;url=/');
            die("购物车为空,跳转至首页");
        }
    }
    /**
     * 添加至购物车
     * @param $goods_id
     */
    public function add($goods_id=0)
    {
        if(empty($goods_id)){
            header('Refresh:3;url=/cart');
            die("请选择商品，3秒后自动跳转至购物车");
        }
        //判断商品是否有效 （有 -》 未下架 -》 未删除 ）
        $goods = GoodsModel::where(['id'=>$goods_id])->first();
        if($goods){
            if($goods->is_delete==1){       //已被删除
                header('Refresh:3;url=/');
                echo "商品已被删除,3秒后跳转至首页";
                die;
            }
            //添加至购物车
            $cart_info = [
                'goods_id'  => $goods_id,
                'goods_name'    => $goods->name,
                'goods_price'    => $goods->price,
                'uid'       => Auth::id(),
                'add_time'  => time(),
                'session_id' => Session::getId()
            ];
            //入库
            $cart_id = CartModel::insertGetId($cart_info);
            if($cart_id){
                header('Refresh:3;url=/cart');
                die("添加购物车成功，自动跳转至购物车");
            }else{
                header('Refresh:3;url=/');
                die("添加购物车失败");
            }
        }else{
            echo "商品不存在";
        }
    }
}