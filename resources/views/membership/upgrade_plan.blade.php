@push('style_top')
<style>
</style>
@endpush
@push('scripts_bottom')
<script>
  var reg_type = true;
  var current_plan = 'pearl'
  const plan_map = (plan='pearl')=> {
    let plan_qty = 1
    if(plan == 'pearl'){
      return `Membership Package: $${100*plan_qty} <br> Minimum Trading Capital: $${20*plan_qty}  <br> Total: $${120*plan_qty}`;
    }else if(plan == 'ruby'){
      return `Membership Package: $${250*plan_qty} <br> Minimum Trading Capital: $${50*plan_qty}  <br> Total: $${300*plan_qty}`;
    }else if(plan == 'gold'){
      return `Membership Package: $${500*plan_qty} <br> Minimum Trading Capital: $${100*plan_qty}  <br> Total: $${600*plan_qty}`;
    }else if(plan == 'sapphire'){
      return `Membership Package: $${1000*plan_qty} <br> Minimum Trading Capital: $${200*plan_qty}  <br> Total: $${1200*plan_qty}`;
    }else if(plan == 'emerald'){
     return `Membership Package: $${3000*plan_qty} <br> Minimum Trading Capital: $${600*plan_qty}  <br> Total: $${3600*plan_qty}`;
    }else{
     return `Membership Package: $${5000*plan_qty} <br> Minimum Trading Capital: $${1000*plan_qty}  <br> Total: $${6000*plan_qty}`;
    }
   };
  function display_package_info(plan) {
    current_plan = plan.value
    document.getElementById('plan_info').innerHTML = plan_map(current_plan,1)
  }
  function display_package_info(plan) {
    document.getElementById('plan_info').innerHTML = plan_map(plan.value)
  }
</script>
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
              <select onchange="display_package_info(this)" class="uk-select uk-border-rounded" name="plan" required
                id="plan">
                <option value="pearl">Pearl($100) </option>
                <option value="ruby">Ruby($250) </option>
                <option value="gold">Gold($500) </option>
                <option value="sapphire">Sapphire($1000) </option>
                <option value="emerald">Emerald($3000) </option>
                <option value="diamond">Diamond($5000) </option>
              </select>
            </div>
          </div>
          @error('amount')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
        <div class="uk-margin uk-width-1-1">
          <div class="uk-form-control">
            <button type="submit"
              class="uk-button uk-border-rounded green accent-2 white-text uk-text-bolder uk-width-1-1">
              <span class="uk-text-large">P</span>ay Upgrade Fee
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
