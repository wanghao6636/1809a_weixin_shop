<table>
    <tr>
        <td>oid</td>
        <td>order_sn</td>
        <td>time</td>
        <td>is_up</td>
    </tr>
    @foreach($list as $k=>$v)
    <tr>
        <td>{{$v['oid']}}</td>
        <td>{{$v['order_sn']}}</td>
        <td>{{$v['time']}}</td>
        <td>{{$v['is_up']}}</td>
    </tr>
    @endforeach
</table>