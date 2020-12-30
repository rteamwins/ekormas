@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'Upgrade Membership')
@section('content')
<div class="uk-container uk-margin-large-top">
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1 uk-align-center uk-width-1-2@s uk-width-1-3@m">
      <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">Upgrade Membership</h2>
      <p class="uk-margin-remove-top">
        Upgrade to a higher membership plan for more benefits
      </p>
      <form method="GET" action="#" class="uk-form-stacked">
        @csrf
        <div class="uk-margin uk-width-1-1">
          <label for="plan" class="uk-form-label">
            Plan <span class="red-text uk-text-bold">*</span>
          </label>
          <div class="uk-form-control">
            <div class="uk-inline uk-width-1-1">
              <select class="uk-select" name="plan" required id="plan">
                <option value=""> -- Select plan -- </option>
                <option value="bronze"> Bronze ($1000) </option>
                <option value="silver">Silver ($2500) </option>
                <option value="gold"> Gold ($7500) </option>
                <option value="ruby"> Ruby ($21000) </option>
                <option value="diamond"> Diamond ($42000) </option>
              </select>
            </div>
            @error('amount')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin uk-width-1-1">
          <div class="uk-form-control">
            <button type="submit" class="uk-button green accent-2 white-text uk-text-bolder uk-width-1-1"
              uk-icon="icon:arrow-right;ratio:1.3">
              Upgrade Now
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
