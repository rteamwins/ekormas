@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Registration Credits')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">REGISTRATION CREDITS</h2>
          <p class="uk-margin-remove-top">
            All Registration Credits Owned
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          @if(in_array(auth()->user()->role,['admin','agent']))
          <a href="{{route('user_purchase_registration_credits')}}"
            class="uk-button uk-button-small uk-position-top-right uk-background-primary white-text"
            uk-icon="cart">Order Now </a>
          @endif
          <registration-credit-list>
          </registration-credit-list>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
