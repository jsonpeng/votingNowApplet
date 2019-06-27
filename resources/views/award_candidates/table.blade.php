<table class="table table-responsive" id="awardCandidates-table">
    <thead>
        <tr>
            <th>Award Id</th>
        <th>Candidate Id</th>
        <th>Num</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($awardCandidates as $awardCandidate)
        <tr>
            <td>{!! $awardCandidate->award_id !!}</td>
            <td>{!! $awardCandidate->candidate_id !!}</td>
            <td>{!! $awardCandidate->num !!}</td>
            <td>
                {!! Form::open(['route' => ['awardCandidates.destroy', $awardCandidate->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('awardCandidates.show', [$awardCandidate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('awardCandidates.edit', [$awardCandidate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>