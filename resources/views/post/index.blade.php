@extends('layouts.app')
@section('title', 'List of Posts')
@section('content')
<div class="uk-child-width-1-1">
  <div>
    <div class="uk-card uk-card-default">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">POST</h2>
          <p class="uk-margin-remove-top">
            All Blog Post
          </p>
        </div>
      </div>
      <div class="uk-card-body uk-padding-small">
        <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-grid-small" uk-grid="masonry: true">
          @foreach ($posts as $post)
          <div>
            <div class="uk-card uk-card-default uk-flex-middle">
              <div class="uk-card-header uk-padding-small">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                  <div class="uk-width-expand">
                    <h4 class="uk-margin-remove-bottom">{{Str::limit($post->title, 35)}}</h4>
                    <p class=" uk-padding-remove uk-text-meta uk-padding-small uk-margin-remove-top">Written
                      @if($post->user != null) by
                      {{$post->user->last_name." ".$post->user->first_name}} @endif on <time
                        datetime="{{$post->created_at}}">{{$post->created_at->toDayDateTimeString()}}</time>
                    </p>
                  </div>
                </div>
              </div>
              @if($post->image != null)
              <div uk-height-viewport="offset-bottom: 70"
                class="uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light"
                data-src="{{URL::to("/images/post/".$post->image)}}" uk-img>
              </div>
              @endif
              <div class="uk-card-body">
                <p>{{Str::limit($post->message, 120)}}</p>
              </div>
              <div class="uk-card-footer  uk-flex-between">
                <a href="{{route('show_post',['id'=> $post->id])}}" class="uk-button uk-button-text">View</a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @if ($posts->hasPages())
      <div class="uk-text-center uk-card-footer">
        {!! $posts->links() !!}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
