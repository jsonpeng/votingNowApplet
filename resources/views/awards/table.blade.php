<table class="table table-responsive" id="awards-table">
    <thead>
        <tr>
            <th>奖项名称</th>
            <th>投票状态</th>
            <th>前端显示状态</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($awards as $award)
        <tr>
            <td>{!! $award->name !!}</td>
            <td>{!! $award->StatusShow !!}  {!! Form::model($award, ['route' => ['awards.upstatus', $award->id], 'method' => 'post']) !!} {!! Form::close()!!}</td>
            <td>{!! $award->ShowStatus !!} {!! Form::model($award, ['route' => ['awards.upshow', $award->id], 'method' => 'post']) !!} {!! Form::close()!!}</td>
            <td>
                {!! Form::open(['route' => ['awards.destroy', $award->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    {{-- <a href="{!! route('awards.show', [$award->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    {{-- <a href="{!! route('awards.edit', [$award->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
       <?php $candidates = app('common')->AwardCandidateRepo()->getCandidates($award->id); ?>

       @foreach ($candidates as $candidate)
       <tr>
          <td>&nbsp;&nbsp;{!! tag('·','black') !!}候选人{!! tag(optional($candidate->candidate)->name,'black') !!},当前总票数{!! tag($candidate->num) !!}</td>
          <td><div class="sign_num" style="display: none;">{!! Form::model($award, ['route' => ['awards.upnum', $award->id,$candidate->candidate->id], 'method' => 'post']) !!}<input type="number" placeholder="请输入新的投票数量" name="num" value="" /><button type="submit" class="btn btn-success">确定</button>{!! Form::close()!!}</div></td>
          <td><a href="javascript:;"  onclick="editStatus(this)" class='btn btn-default btn-xs'>修改投票数量</a></td>
      </div>
       @endforeach
    @endforeach
    </tbody>
</table>