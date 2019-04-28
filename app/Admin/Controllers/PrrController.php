<?php

namespace App\Admin\Controllers;

use App\Model\PrrModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Support\Facades\Cache;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp\Client;
class PrrController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
//        $access_token=$this->access_token();
        $access = $this->getaccessToken();
        $file=file('file');
        var_dump($file);exit;
       // var_dump($access);exit;
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$access&type=image";
       // var_dump($url);exit;
        $Client=new Client();
        //var_dump($Clien);exit;
//        $response=$Client->request('post',$url,[
//                'multipart'=>[
//                [
//                    'name'=>'media',
//                    'contents'=>fopen('image/goods_jpg','r'),
//                ]
//            ]
//        ]);
//        //var_dump($response);exit;

        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }


    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PrrModel);



        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(PrrModel::findOrFail($id));



        return $show;
    }
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
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PrrModel);



        return $form;
    }


}
