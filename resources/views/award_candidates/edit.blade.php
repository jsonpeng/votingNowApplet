@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Award Candidate
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($awardCandidate, ['route' => ['awardCandidates.update', $awardCandidate->id], 'method' => 'patch']) !!}

                        @include('award_candidates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection