@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'Request Withdrawal')
@section('content')
<div class="uk-container uk-margin-large-top">
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1 uk-align-center uk-width-1-2@s uk-width-1-3@m">
      <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">WITHDRAW NOW</h2>
      <p class="uk-margin-remove-top">
        Request a withdrawal from your account
      </p>
      <form method="GET" action="#" class="uk-form-stacked">
        @csrf
        <div class="uk-margin uk-width-1-1">
          <label for="source" class="uk-form-label">
            Source <span class="red-text uk-text-bold">*</span>
          </label>
          <div class="uk-form-control">
            <div class="uk-inline uk-width-1-1">
              <select class="uk-select" name="source" required id="source">
                <option value=""> -- Select Source -- </option>
                <option value="wallet"> Wallet ($4343.2344) </option>
                <option value="bonus"> Bonus ($523.3242) </option>
              </select>
            </div>
            @error('amount')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin uk-width-1-1">
          <label for="amount" class="uk-form-label">
            Withdrawing Amount <span class="red-text uk-text-bold">*</span>
          </label>
          <div class="uk-form-control">
            <div class="uk-inline uk-width-1-1">
              <span class="uk-form-icon">$</span>
              <input class="uk-input @error('amount') uk-form-danger @enderror" name="amount" id="amount" type="number"
                min="100" value="{{ old('amount') }}" required autofocus>
            </div>
            @error('amount')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin uk-width-1-1">
          <label for="amount" class="uk-form-label">
            Bitcoin Wallet <span class="red-text uk-text-bold">*</span>
          </label>
          <div class="uk-form-control">
            <div class="uk-inline uk-width-1-1">
              <span class="uk-form-icon">&#8383;</span>
              <input class="uk-input @error('amount') uk-form-danger @enderror" name="amount" id="amount" type="text"
                minlength="20" value="{{ old('amount') }}" required autofocus>
            </div>
            @error('amount')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin uk-width-1-1">
          <div class="uk-form-control">
            <button type="submit"
              class="uk-button orange darken-2 white-text uk-text-bolder uk-width-1-1"
              uk-icon="icon:arrow-right;ratio:1.3">
              Request Withdraw
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
