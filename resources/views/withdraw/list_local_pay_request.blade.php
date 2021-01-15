@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Local Withdraw Request')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">LOCAL PAY WITHDRAWAL REQUEST</h2>
          <p class="uk-margin-remove-top">
            All local Withdrawal Requested From You
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <local-pay-request-list></local-pay-request-list>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
