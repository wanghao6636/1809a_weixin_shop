<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <!-- Styles -->

</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">

        <div id="qrcode"></div>
        <div class="title m-b-md">
            {{--<input type="button" value="转发">--}}
            <table border="1">
                <tr>
                    <td>ID</td>
                    <td>名称</td>
                    <td>价格</td>
                    <td>数量</td>
                </tr>
                @foreach($list as $k=>$v)
                    <tr>
                        <td>{{$v['id']}}</td>
                        <td><a href="key?id={{$v['id']}}">{{$v['name']}}</a></td>
                        <td>{{$v['price']}}</td>
                        <td>{{$v['num']}}</td>

                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/qrcode.min.js"></script>
<script>
    var recode=   new QRCode(document.getElementById("qrcode"),{
        text:"{{$tkurl}}",
        width:150,
        height:150,
        colorDark:'#000000',
        colorLight:'#ffffff',
        correctLevel:QRCode.CorrectLevel.H
    } );

</script>

</body>
</html>



{{--<script>--}}
    {{--var qrcode = new QRCode('qrcode',{--}}
        {{--text:'{{$url}}',--}}
        {{--width:256,--}}
        {{--height:256,--}}
        {{--colorDark : '#000000',--}}
        {{--colorLight : '#ffffff',--}}
        {{--correctLevel : QRCode.CorrectLevel.H--}}
    {{--});--}}
{{--</script>--}}




