@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'Fund Wallet')
@section('content')
<div class="uk-container uk-padding-remove">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">WALLET TOP-UP</h2>
          <p class="uk-margin-remove-top">
            Add more funds to your wallet.
          </p>
        </div>

        <div class="uk-card-body uk-padding-small">

          <div class="uk-margin-large-bottom uk-grid-divider uk-child-width-1-1 uk-child-width-1-2@s" uk-grid>
            <div>
              <form method="POST" id="btc_funding" action="{{route('user_fund_wallet_save')}}"
                class="uk-form-stacked uk-flex uk-flex-column">
                @csrf
                <h5 class="uk-h5 uk-margin-remove-vertical uk-text-center uk-text-bold">USE BITCOIN</h5>
                <div class="uk-divider-icon"></div>
                <div class="uk-margin uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center">
                  <label for="funding_amount" class="uk-form-label">
                    Amount *
                  </label>
                  <div class="uk-form-control uk-width-1-1">
                    <div class="uk-inline uk-width-1-1">
                      <span class="uk-form-icon">$</span>
                      <input class="uk-input uk-border-rounded @error('funding_amount') uk-form-danger @enderror"
                        name="funding_amount" id="funding_amount" type="number" value="{{ old('funding_amount') }}"
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
                      class="uk-button uk-border-rounded orange darken-2 white-text uk-text-bolder uk-width-1-1">
                      <span class="uk-text-large">&#8383;</span>itcoin
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <div>
              <form method="POST" id="kyc_funding" action="{{route('user_fund_wallet_save')}}"
                class="uk-form-stacked uk-flex uk-flex-column">
                @csrf
                <h5 class="uk-h5 uk-margin-remove-vertical uk-text-center uk-text-bold">USE KYC INSTEAD</h5>
                <div class="uk-divider-icon"></div>
                <div class="uk-margin uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center">
                  <label for="funding_kyc_code" class="uk-form-label">
                    KYC Code *
                  </label>
                  <div class="uk-form-control uk-width-1-1">
                    <div class="uk-inline uk-width-1-1">
                      <span class="uk-form-icon">#</span>
                      <input class="uk-input uk-border-rounded @error('funding_kyc_code') uk-form-danger @enderror"
                        name="funding_kyc_code" id="funding_kyc_code" type="text" value="{{ old('funding_kyc_code') }}"
                        required>
                    </div>
                    @error('funding_kyc_code')
                    <span class="uk-text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="uk-margin-remove-top uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center">
                  <div class="uk-form-control">
                    <button type="submit"
                      class="uk-button uk-border-rounded blue darken-2 white-text uk-text-bolder uk-width-1-1">
                      <span class="uk-text-large">K</span>YC
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
