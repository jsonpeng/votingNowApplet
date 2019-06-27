<!-- Award Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('award_id', 'Award Id:') !!}
    {!! Form::text('award_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Candidate Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('candidate_id', 'Candidate Id:') !!}
    {!! Form::text('candidate_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Num Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('num', 'Num:') !!}
    {!! Form::text('num', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('awardCandidates.index') !!}" class="btn btn-default">Cancel</a>
</div>
