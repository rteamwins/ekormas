@extends('layouts.app')
@section('title', $post->title)
@section('content')
<div class="uk-margin-large-bottom uk-flex-center" uk-grid>
  <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
    <div class="uk-card uk-card-default uk-border-rounded">
      <div class="uk-card-header">
        <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">{{$post->title}}</h2>
        <p class="uk-margin-remove-top">
          Written @if($post->user != null) by
          {{$post->user->name}} @endif
          on <time
            datetime="{{$post->created_at->toDateTimeString()}}">{{$post->created_at->toDayDateTimeString()}}</time>

        </p>
      </div>
      @if($post->image != null)
      <div uk-height-viewport="offset-bottom: 70"
        class="uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light"
        data-src="{{URL::to("/images/post/".$post->image)}}" uk-img>
      </div>
      @endif
      <div class="uk-card-body">
        <p>{{$post->message}}</p>
      </div>
      <div class="uk-card-footer uk-flex uk-flex-between">
        <a href="{{url()->previous()}}" class="uk-button uk-button-text">Go Back</a>
        <div>
          <a href="{{route('edit_post',['id' => $post->id])}}" class="uk-button uk-button-text blue-text">Edit</a>
          <a href="{{route('delete_post',['id' => $post->id])}}" class="uk-button uk-button-text red-text">Delete
            Post</a>
          @if($post->image != null)
          <a href="{{route('delete_post_image',['id' => $post->id])}}" class="uk-button uk-button-text red-text">Delete
            Image</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
