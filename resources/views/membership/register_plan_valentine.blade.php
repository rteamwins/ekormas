@push('style_top')
<style>
</style>
@endpush
@push('scripts_bottom')
<script>
  var btc_input = '<label for="plan" class="uk-form-label">Plan <span class="red-text uk-text-bold">*</span></label><div class="uk-form-control"><div class="uk-inline uk-width-1-1"><select onchange="display_package_info(this)" class="uk-select uk-border-rounded" name="plan" required id="plan"><option value="onyx_valentine">Oynx Valentine($40) </option><option value="pearl_valentine">Pearl Valentine($80) </option><option value="ruby_valentine">Ruby Valentine($200) </option><option value="gold_valentine">Gold Valentine($400) </option><option value="sapphire_valentine">Sapphire Valentine($800) </option><option value="emerald_valentine">Emerald($2400) </option><option value="diamond_valentine">Diamond Valentine($4000) </option></select></div>@error('plan')<span class="uk-text-danger">{{ $message }}</span>@enderror</div>'
  var rc_input ='<label for="rc_code" class="uk-form-label">RC Code <span class="red-text uk-text-bold">*</span></label><div class="uk-form-control uk-width-1-1"><div class="uk-inline uk-width-1-1"><span class="uk-form-icon" uk-icon="hashtag"></span><input class="uk-input uk-border-rounded @error('rc_code') uk-form-danger @enderror" name="rc_code" type="text" value="{{ old('rc_code') }}" required></div>@error('rc_code')<span class="uk-text-danger">{{ $message }}</span>@enderror</div>'
  var rc_submit = '<span class="uk-text-large">R</span>egister Now';
  var btc_submit = '<span class="uk-text-large">P</span>ay Registration Fee';
  var reg_type = true;
  var current_plan = 'onyx_valentine'
  const plan_map = (plan='onyx_valentine')=> {
    let plan_qty = 1
    if(plan == 'onyx_valentine'){
      return `Membership Package: $${40*plan_qty} <br> Minimum Trading Capital: $${10*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${60*plan_qty}`;
    }else if(plan == 'pearl_valentine'){
      return `Membership Package: $${80*plan_qty} <br> Minimum Trading Capital: $${20*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${110*plan_qty}`;
    }else if(plan == 'ruby_valentine'){
      return `Membership Package: $${200*plan_qty} <br> Minimum Trading Capital: $${50*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${215*plan_qty}`;
    }else if(plan == 'gold_valentine'){
      return `Membership Package: $${400*plan_qty} <br> Minimum Trading Capital: $${100*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${510*plan_qty}`;
    }else if(plan == 'sapphire_valentine'){
      return `Membership Package: $${800*plan_qty} <br> Minimum Trading Capital: $${200*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${1010*plan_qty}`;
    }else if(plan == 'emerald_valentine'){
     return `Membership Package: $${2400*plan_qty} <br> Minimum Trading Capital: $${600*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${3010*plan_qty}`;
    }else{
     return `Membership Package: $${4000*plan_qty} <br> Minimum Trading Capital: $${1000*plan_qty} <br> Registration Fee: $${10*plan_qty} <br> Total: $${5010*plan_qty}`;
    }
   };
  function display_package_info(plan) {
    current_plan = plan.value
    document.getElementById('plan_info').innerHTML = plan_map(current_plan,1)
  }

  function toggle_reg_mode(new_mode){
    reg_type = new_mode
    if(reg_type){
      document.getElementById('reg_type_input').innerHTML=btc_input
      document.getElementById('reg_submit').innerHTML=btc_submit
      document.getElementById('plan_info').innerHTML = plan_map()
    }else{
      document.getElementById('reg_type_input').innerHTML=rc_input
      document.getElementById('reg_submit').innerHTML=rc_submit
      document.getElementById('plan_info').innerText = 'Enter the Registration Credit Code You Have.'
    }
  }
  function display_package_info(plan) {
    document.getElementById('plan_info').innerHTML = plan_map(plan.value)
  }
</script>
@endpush
@extends('layouts.app')
@section('title', 'Choose Membership')
@section('content')
<div class="uk-container uk-margin-large-top">
  <div class="uk-width-1-1 uk-text-center">
    <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">Choose Membership</h2>
    <p class="uk-margin-remove-top">
      Choose a membership plan to complete your registration
    </p>
  </div>
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-1-2@m">
      <ul class="uk-subnav uk-subnav-pill uk-text-bold uk-text-center uk-child-width-expand" uk-switcher>
        <li><a href="#" onclick="toggle_reg_mode(true)"><span></span> &#8383;BTC</a></li>
        <li><a href="#" onclick="toggle_reg_mode(false)">REGISTRATION CREDIT</a></li>
      </ul>
      <div class="uk-divider-icon"></div>
      <div>
        <form method="POST" action="{{ route('process_val_reg_plan') }}" class="uk-form-stacked">
          @csrf
          <div class="uk-width-1-1">
            <span id="reg_type_input">
              <label for="plan" class="uk-form-label">
                Plan <span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <select onchange="display_package_info(this)" class="uk-select uk-border-rounded" name="plan" required
                    id="plan">
                    <option value="onyx_valentine">Oynx Valentine($40) </option>
                    <option value="pearl_valentine">Pearl Valentine($80) </option>
                    <option value="ruby_valentine">Ruby Valentine($200) </option>
                    <option value="gold_valentine">Gold Valentine($400) </option>
                    <option value="sapphire_valentine">Sapphire Valentine($800) </option>
                    <option value="emerald_valentine">Emerald($2400) </option>
                    <option value="diamond_valentine">Diamond Valentine($4000) </option>
                  </select>
                </div>
                @error('plan')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </span>
          </div>
          <div class="uk-margin uk-width-1-1">
            <div class="uk-alert-primary" id="plan_info_alert" uk-alert>
              <p class="uk-text-center uk-text-bold" id="plan_info">Membership Package: $40 <br> Minimum
                Trading Capital: $10 <br> Registration Fee: $10 <br> Total: $60</p>
            </div>
          </div>
          <div class="uk-margin uk-width-1-1">
            <div class="uk-form-control">
              <button id="reg_submit" type="submit"
                class="uk-button uk-border-rounded green accent-2 white-text uk-text-bolder uk-width-1-1">
                <span class="uk-text-large">P</span>ay Registration Fee
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
