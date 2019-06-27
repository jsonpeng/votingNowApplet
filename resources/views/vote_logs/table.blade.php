<table class="table table-responsive" id="voteLogs-table">
    <thead>
        <tr>
        <th>投票人</th>
        <th>投票奖项</th>
        <th>所投候选人</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($voteLogs as $voteLog)
        <tr>
            <td>{!! $voteLog->UserName !!}</td>
            <td>{!! $voteLog->AwardName !!}</td>
            <td>{!! $voteLog->CandidateName !!}</td>
            <td>
                {!! Form::open(['route' => ['voteLogs.destroy', $voteLog->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    {{-- <a href="{!! route('voteLogs.show', [$voteLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    {{-- <a href="{!! route('voteLogs.edit', [$voteLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>