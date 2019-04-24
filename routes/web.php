<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//购物车
Route::get('cart', 'CartController@index');
//添加至购物车
Route::get('/cart/add/{goods_id?}', 'CartController@add');      
//订单处理
Route::get('/order/list', 'Order\IndexController@oList');      
//生成订单
Route::get('/order/create', 'Order\IndexController@create');      
//查询订单支付状态
Route::get('/order/paystatus', 'Order\IndexController@payStatus');      
//微信支付
Route::get('/pay/weixin', 'Weixin\PayController@pay');     
//微信支付通知回调
Route::post('/weixin/pay/notify', 'Weixin\PayController@notify');      
//微信支付成功
Route::get('/pay/success', 'Weixin\PayController@paySuccess');


//详情页
Route::get('/index', 'Order\KeyController@index');
//点击次数
Route::get('/key', 'Order\KeyController@key');

//浏览排序
Route::get('/getSort', 'Order\KeyController@getSort');

//用哈希来存缓存
Route::get('/cacheGoods', 'Order\KeyController@cacheGoods');

//微信jssdk
Route::get('/jsTest', 'Weixin\jssdkController@jsTest');         //微信测试
Route::get('/getImg', 'Weixin\jssdkController@getImg');         //获取到jssdk上传的照片



//图文
Route::get('/valid','Weixin\ZffController@wxEvent');



