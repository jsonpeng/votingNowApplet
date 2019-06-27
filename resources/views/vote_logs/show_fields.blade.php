<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $voteLog->id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $voteLog->user_id !!}</p>
</div>

<!-- Award Id Field -->
<div class="form-group">
    {!! Form::label('award_id', 'Award Id:') !!}
    <p>{!! $voteLog->award_id !!}</p>
</div>

<!-- Candidate Id Field -->
<div class="form-group">
    {!! Form::label('candidate_id', 'Candidate Id:') !!}
    <p>{!! $voteLog->candidate_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $voteLog->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $voteLog->updated_at !!}</p>
</div>

