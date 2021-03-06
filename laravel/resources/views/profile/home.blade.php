@extends('app')

@section('title', $user->name. 'のプロフィール')

@section('content')
  @include('nav')
  <div class="l-container--content">
    <div class="l-container--wrapper">
      <div class="l-container--layout--lg l-container--layout--lg--sp">
        <div class="l-container--border">
          <div class="c-user--box">
            <div class="c-user--photo">
              @if (!isset($user->profile_image))
                <img src="{{ asset('img/no_image.jpg')}}" alt="" class="c-user--image--lg">
              @else
                <img src="{{ asset($user->profile_image) }}" alt="" class="c-user--image--lg">
              @endif
            </div>
            <div class="c-user--body">
              <div class="c-user--detail">
                <h3 class="c-user--name">{{ $user->name }}</h3>
              </div>
              <div class="c-user--professional--category">
                @foreach($categories as $category)
                  <p class="c-user--categoryName">
                    {{ $category->name }}
                  </p>
                @endforeach
              </div>
              <div class="c-user--review  c-user--review--center">
                <i class="far fa-comment-alt fa-lg"></i>
                {{ $user->revieweds()->count()}}件
              </div>
              <div class="c-user--profile">
                <p class="c-user--edit"><a href="{{ route('profile.edit')}}">プロフィール編集</a></p>
              </div>
              <p class="c-user--intro">{{ $user->intro}}</p>
            </div>
            <div class="c-user--feature c-user--feature--profile">
              <div class="c-user--Container">
                <h5 class="c-user--featureTitle--profile">興味・関心のある分野</h5>
                <div class="c-user--featureArea--profile">
                  <p class="c-user--body">
                    {{ $user->talk_theme }}
                  </p>
                </div>
              </div>
              <div class="c-user--Container">
                <h5 class="c-user--featureTitle--profile">こんな方と話したい</h5>
                <div class="c-user--featureArea--profile">
                  <p class="c-user--featureBody">
                    {{ $user->speaking }}
                  </p>
                </div>
              </div>
            </div>
            <div class="c-user--reviewArea">
              <h5 class="c-user--myName">{{ $user->name}}さんのレビュー</h5>
            </div>
            <div class="c-user--reviewContainer">
              @foreach($reviews as $review)
                <div class="c-user--card">
                  <div class="c-user--member">
                    <div class="c-user--imageArea">
                      <a href="{{ route('users.show', ['name' => $review->reviewer->name])}}">
                        @if (!isset($review->reviewer->profile_image))
                          <img src="{{asset('img/no_image.jpg')}}" alt="" class="c-user--image--sm--review">
                        @else
                          <img src="{{$review->reviewer->profile_image}}" alt="" class="c-user--image--sm--review">
                        @endif
                      </a>
                      <span class="c-user--other">{{ $review->reviewer->name}}</span>
                    </div>
                  </div>
                  <div class="c-user--date--review">
                    <span class="">{{ $review->created_at->format('Y-m-d')}}</span>
                  </div>
                  <div class="c-user--reviewTitle">
                    <p class="c-user--title">{{ $review->title}}</p>
                    <p class="c-user--rate">
                      <comment-star
                        rating={{ $review->star }}
                        :star-size=25
                        :read-only=true
                      >
                      </comment-star>
                    </p>
                    <p class="c-user--detail">
                      {{ $review->body}}
                    </p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection