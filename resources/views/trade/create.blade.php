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
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">TRADE NOW</h2>
          <p class="uk-margin-remove-top">
            Start a Trade Session now
          </p>
        </div>

        <div class="uk-card-body uk-padding-small">
          <form method="POST" action="{{route('user_trade_save')}}" class="uk-form-stacked">
            @csrf
            <div class="uk-margin uk-width-1-1">
              <label for="trade_type" class="uk-form-label">
                Trading Type *
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <select class="uk-select" name="trade_type" id="trade_type">
                    <option value=""> -- Select Type -- </option>
                    <option value="0"> Manual [7 Days] </option>
                    <option value="1"> Automatic [1 Month]</option>
                    <option value="2"> Automatic [2 Months] </option>
                    <option value="3"> Automatic [3 Months] </option>
                  </select>
                </div>
                @error('trade_type')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1">
              <label for="trade_amount" class="uk-form-label">
                Trading Amount *
              </label>
              <label for="trade_amount" class="uk-form-label">
                Min ${{number_format(Auth()->user()->membership_plan->min_trading_capital,0)}} - Max
                ${{number_format(Auth()->user()->wallet >= Auth()->user()->membership_plan->max_trading_capital?Auth()->user()->membership_plan->max_trading_capital:Auth()->user()->wallet,0)}}
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon">$</span>
                  <input class="uk-input @error('trade_amount') uk-form-danger @enderror" name="trade_amount"
                    id="trade_amount" type="number"
                    max="{{Auth()->user()->wallet >= Auth()->user()->membership_plan->max_trading_capital?Auth()->user()->membership_plan->max_trading_capital:Auth()->user()->wallet}}"
                    min="{{Auth()->user()->membership_plan->min_trading_capital}}"
                    value="{{ old('trade_amount')?:Auth()->user()->membership_plan->min_trading_capital }}" required
                    autofocus>
                </div>
                @error('trade_amount')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1">
              <div class="uk-form-control">
                <button type="submit"
                  class="uk-button green accent-2 white-text uk-text-bolder uk-width-1-1 uk-width-1-2@m"
                  uk-icon="icon:arrow-right;ratio:1.3">
                  <span class="uk-text-large">T</span>rade Now

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
