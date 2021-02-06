@push('style_top')
<style>
</style>
@endpush
@push('scripts_bottom')
<script>
  var reg_type = true;
  var current_plan = 'onyx'
  const plan_map = (plan='onyx')=> {
    let plan_qty = 1
    if(plan == 'onyx'){
      return `Membership Package: $${50*plan_qty} <br> Minimum Trading Capital: $${10*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${70*plan_qty}`;
    }else if(plan == 'pearl'){
      return `Membership Package: $${100*plan_qty} <br> Minimum Trading Capital: $${20*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${130*plan_qty}`;
    }else if(plan == 'ruby'){
      return `Membership Package: $${250*plan_qty} <br> Minimum Trading Capital: $${50*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${310*plan_qty}`;
    }else if(plan == 'gold'){
      return `Membership Package: $${500*plan_qty} <br> Minimum Trading Capital: $${100*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${610*plan_qty}`;
    }else if(plan == 'sapphire'){
      return `Membership Package: $${1000*plan_qty} <br> Minimum Trading Capital: $${200*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${1210*plan_qty}`;
    }else if(plan == 'emerald'){
     return `Membership Package: $${3000*plan_qty} <br> Minimum Trading Capital: $${600*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${3610*plan_qty}`;
    }else{
     return `Membership Package: $${5000*plan_qty} <br> Minimum Trading Capital: $${1000*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${6010*plan_qty}`;
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
@section('title', 'Gift Registration Credit')
@section('content')
<div class="uk-container uk-padding-remove">
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">GIFT REGISTRATION CREDIT</h2>
          <p class="uk-margin-remove-top">
            Gift Registration credit to a specific user
          </p>
        </div>
        <div class="uk-card-body uk-padding-small">
          <form method="POST" id="btc_funding" action="{{route('admin_store_gift_registration_credits')}}"
            class="uk-form-stacked uk-flex uk-flex-column">
            @csrf
            <div class="uk-margin uk-width-1-1">
              <label for="receiver_username" class="uk-form-label">
                Receiver Username *
              </label>
              <div class="uk-form-control uk-width-1-1">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon" uk-icon="user"></span>
                  <input class="uk-input uk-border-rounded @error('receiver_username') uk-form-danger @enderror"
                    name="receiver_username" id="receiver_username" type="text" value="{{ old('receiver_username') }}"
                    required autofocus>
                </div>
                @error('receiver_username')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="" uk-grid>
              <div class="uk-width-1-2">
                <label for="plan" class="uk-form-label">
                  Plan <span class="red-text uk-text-bold">*</span>
                </label>
                <div class="uk-form-control">
                  <div class="uk-inline uk-width-1-1">
                    <select onchange="display_package_info(this)" class="uk-select uk-border-rounded" name="plan"
                      required id="plan">
                      <option value="onyx">Onyx($50) </option>
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
              <div class="uk-width-1-2">
                <label for="quantity" class="uk-form-label">
                  Plan Quantity *
                </label>
                <div class="uk-form-control uk-width-1-1">
                  <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon">#</span>
                    <input class="uk-input uk-border-rounded @error('quantity') uk-form-danger @enderror"
                      name="quantity" id="quantity" type="text" value="{{ old('quantity') }}" required type="number"
                      min="1" max="50">
                  </div>
                  @error('quantity')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="uk-margin uk-width-1-1">
              <div class="uk-alert-primary" id="plan_info_alert" uk-alert>
                <p class="uk-text-center uk-text-bold" id="plan_info">Membership Package: $50 <br> Minimum
                  Trading Capital: $10 <br> Registration Fee: $10 <br> Total: $70</p>
              </div>
            </div>
            <div class="uk-margin-remove-top uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center">
              <div class="uk-form-control">
                <button type="submit"
                  class="uk-button uk-border-rounded green accent-2 white-text uk-text-bolder uk-width-1-1">
                  <span class="uk-text-large">G</span>ift
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
