@push('style_top')
<style>
  .agent_logo {
    height: 40px;
    width: 40px;
    object-fit: cover;
  }
</style>
@endpush
@extends('layouts.app')
@section('title', 'Member\'s Dashboard')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom uk-margin-large-bottom">
  @include('layouts.user_stats_card')
  <div class="uk-grid-small" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default">
        <div class="uk-card-body uk-padding-remove">
          <span class="uk-padding-small uk-border-circle" uk-icon="icon:cart; ratio:1.5"></span>
          <span class="uk-label uk-float-right">34</span>
          <line-chart :height="130" :chartdata="{{ json_encode($pdata) }}" :options="{{ json_encode($options) }}" />
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
