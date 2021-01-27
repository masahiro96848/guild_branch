@extends('app')

@section('title', 'メンバー募集を編集する')

@section('content')
  @include('nav')
  <div class="l-container">
    <div class="l-container--title">
      <h2 class="l-container--wrapper--center">仲間・メンバー募集を作る</h2>
    </div>
    <div class="l-container--wrapper u-pt_40 u-width_100">
      <div class="l-container--form">
        @include('error')
        <form method="POST" action="{{ route('job.update', ['id' => $job->id])}}" enctype="">
          @csrf
          @method('PUT')
            @include('job.form')
          <button class="c-button c-button--submit " type="submit">公開する</button>
        </form>
      </div>
    </div>
  </div>
  @include('footer')  
@endsection