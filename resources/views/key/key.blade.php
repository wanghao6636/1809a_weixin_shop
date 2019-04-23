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
    <table border="1">
        <tr>
            <td>ID</td>
            <td>名称</td>
            <td>价格</td>
            <td>数量</td>
            <td>点击次数</td>
        </tr>
        @foreach($list as $k=>$v)
        <tr>
            <td>{{$v['id']}}</td>
            <td><a href="key?id={{$v['id']}}">{{$v['name']}}</a></td>
            <td>{{$v['price']}}</td>
            <td>{{$v['num']}}</td>
            <td></td>
        </tr>
        @endforeach
    </table>
</body>
</html>