<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td>:</td>
            <td>:</td>
            <td>:</td>
        </tr>
        <tr>
            <td>{{$goods_name->name}}</td>
            <td>{{$goods_price->price}}}</td>
            <td></td>
        </tr>
    </table>
        <scrpit src="http://res2.wx.qq.com/js/jweixin-1.4.0.js "></scrpit>
        <scrpit>
            wx.config({
            //debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "{{$jsconfig['appId']}}", // 必填，公众号的唯一标识
            timestamp: "{{$jsconfig['timestamp']}}", // 必填，生成签名的时间戳
            nonceStr: "{{$jsconfig['nonceStr']}}", // 必填，生成签名的随机串
            signature: "{{$jsconfig['signature']}}",// 必填，签名
            jsApiList: ['chooseImage','uploadImage','updateAppMessageShareData'] // 必填，需要使用的JS接口列表
            });

            wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
            wx.updateAppMessageShareData({
            title: '标题', // 分享标题
            desc: '123456', // 分享描述
            link: 'http://1809wanghao.comcto.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://pic.sogou.com/pics/recompic/detail.jsp?category=%E6%90%9E%E7%AC%91&tag=%E6%90%9E%E7%AC%91%E4%BA%BA%E7%89%A9#14%26487681', // 分享图标
            success: function () {
            alert('分享成功');
                    }
                })
            });
        </scrpit>

</body>
</html>