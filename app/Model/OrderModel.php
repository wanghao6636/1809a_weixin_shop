<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class OrderModel extends Model
{
    //
    protected $table = 'weixin_orders';
    public $timestamps = false;
    /**
     * 生成订单编号
     */
    public static function generateOrderSN($uid)
    {
        $order_sn = '1809a_weixin_'. date("ymdH").'_';
        $str = time() . $uid . rand(1111,9999) . Str::random(16);
        $order_sn .=  substr(md5($str),5,16);
        return $order_sn;
    }
}