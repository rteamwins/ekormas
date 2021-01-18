@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'Fund Wallet With Point')
@section('content')
<div class="uk-container uk-padding-remove">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">WALLET TOP-UP</h2>
          <p class="uk-margin-remove-top">
            Add more funds to your wallet.
          </p>
        </div>
        <div class="uk-card-body uk-padding-small">
          <form method="POST" id="wallet_funding" action="{{route('save_point_to_wallet_funds')}}"
            class="uk-form-stacked uk-flex uk-flex-column">
            @csrf
            <div class="uk-margin uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center">
              <label for="funding_amount" class="uk-form-label">
                Amount *
              </label>
              <div class="uk-form-control uk-width-1-1">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon">$</span>
                  <input class="uk-input uk-border-rounded @error('funding_amount') uk-form-danger @enderror"
                    name="funding_amount"
                    id="funding_amount"
                    type="number"
                    value="{{ old('funding_amount') }}"
                    min="100" required autofocus>
                </div>
                @error('funding_amount')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin-remove-top uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center">
              <div class="uk-form-control">
                <button type="submit"
                  class="uk-button uk-border-rounded green accent-2 white-text uk-text-bolder uk-width-1-1">
                  <span class="uk-text-large">C</span>onvert
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
