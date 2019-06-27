<table class="table table-responsive" id="participants-table">
    <thead>
        <tr>
            <th>姓名</th>
            <th>登录状态</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($participants as $participant)
        <tr>
            <td>{!! $participant->name !!}</td>
            <td>{!! $participant->is_login ? '已登录' : '未登录' !!}</td>
            <td>
             
                <div class='btn-group'>

                    @if($participant->is_login)
                            {!! Form::model($participant, ['route' => ['participants.setlogout', $participant->id], 'method' => 'post']) !!}
                                    <a href="javascript:;" onclick="$(this).parent().submit();" class='btn btn-default btn-xs'>取消登录</a>
                            {!! Form::close() !!}
                    @endif

                    {{-- <a href="{!! route('participants.show', [$participant->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    {{-- <a href="{!! route('participants.edit', [$participant->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}

                            {!! Form::open(['route' => ['participants.destroy', $participant->id], 'method' => 'delete']) !!}
                                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                            {!! Form::close() !!}
                    
                </div>
             
            </td>
        </tr>
    @endforeach
    </tbody>
</table>