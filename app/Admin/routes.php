<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    //商品
    $router->resource('goods', GoodsController::class);
    //微信用户列表
    $router->resource('Stac', StacController::class);
    //订单
    $router->resource('order', OrdersController::class);

    $router->resource('index', PrrController::class);
});
