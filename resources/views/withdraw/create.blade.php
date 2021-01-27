@push('style_top')
<style>
</style>
@endpush
@push('scripts_bottom')
<script src="{{asset('js/wallet-address-validator.min.js')}}"></script>
<script>
  const agents = @json($agents);
  const val_btc_address = (address)=>{
    let wallet_warning = document.getElementById('wallet_warning')
    if(WAValidator.validate(address, 'bitcoin')){
      wallet_warning.textContent = 'Valid Bitcoin Wallet Address';
      wallet_warning.classList.toggle('uk-text-danger',false)
      wallet_warning.classList.toggle('uk-text-success',true)
      wallet_warning.classList.toggle('uk-hidden',false)
    }else{
      wallet_warning.textContent = 'Invalid Bitcoin Wallet Address';
      wallet_warning.classList.toggle('uk-text-success',false)
      wallet_warning.classList.toggle('uk-text-danger',true)
      wallet_warning.classList.toggle('uk-hidden',false)
    }
  }


  const calc_service_fee = (fee)=>{
    let user_max_withdraw_amount = {{(Auth()->user()->wallet * ((100-5) / 100))}};
    let amount_warning = document.getElementById('amount_warning')
    if(fee > user_max_withdraw_amount){
      amount_warning.textContent = 'You Do Not Have Sufficent Funds';
      amount_warning.classList.toggle('uk-text-danger',true)
      amount_warning.classList.toggle('uk-text-success',false)
      amount_warning.classList.toggle('uk-hidden',false)
    }else if(fee < 100){
      amount_warning.textContent = 'Mininum Withdrawal is $100';
      amount_warning.classList.toggle('uk-text-danger',true)
      amount_warning.classList.toggle('uk-text-success',false)
      amount_warning.classList.toggle('uk-hidden',false)
    }else{
      amount_warning.textContent = 'Service Charge: $'+((5/100)*fee).toFixed(2);
      amount_warning.classList.toggle('uk-text-success',true)
      amount_warning.classList.toggle('uk-text-danger',false)
      amount_warning.classList.toggle('uk-hidden',false)
    }
  }

  const set_withdraw_type =(type)=>{
    let wallet_con = document.getElementById('btc_wallet_container')
    let wallet_input =document.getElementById('btc_address')

    let local_agent_con =document.getElementById('local_pay_container')
    let local_agent =document.getElementById('agent')

    let medium_name_con =document.getElementById('medium_name_container')
    let medium_name =document.getElementById('medium_name')

    let medium_account_name_con =document.getElementById('medium_account_name_container')
    let medium_account_name =document.getElementById('medium_account_name')

    let medium_account_number =document.getElementById('medium_account_number')
    let medium_account_number_con =document.getElementById('medium_account_number_container')

    if(type == 'bitcoin'){
      wallet_input.removeAttribute('disabled')
      wallet_input.setAttribute('required',true)
      wallet_con.classList.toggle('uk-hidden',false)

      local_agent.setAttribute('disabled',true)
      local_agent.removeAttribute('required')
      local_agent_con.classList.toggle('uk-hidden',true)

      medium_name.setAttribute('disabled',true)
      medium_name.removeAttribute('required')
      medium_name_con.classList.toggle('uk-hidden',true)

      medium_account_name.setAttribute('disabled',true)
      medium_account_name.removeAttribute('required')
      medium_account_name_con.classList.toggle('uk-hidden',true)

      medium_account_number.setAttribute('disabled',true)
      medium_account_number.removeAttribute('required')
      medium_account_number_con.classList.toggle('uk-hidden',true)

    }else if(type == 'local'){
      wallet_input.setAttribute('disabled', true)
      wallet_input.removeAttribute('required')
      wallet_con.classList.toggle('uk-hidden',true)

      local_agent.removeAttribute('disabled')
      local_agent.setAttribute('required',true)
      local_agent_con.classList.toggle('uk-hidden',false)

      medium_name.removeAttribute('disabled')
      medium_name.setAttribute('required', true)
      medium_name_con.classList.toggle('uk-hidden',false)

      medium_account_name.removeAttribute('disabled')
      medium_account_name.setAttribute('required',true)
      medium_account_name_con.classList.toggle('uk-hidden',false)

      medium_account_number.removeAttribute('disabled')
      medium_account_number.setAttribute('required', true)
      medium_account_number_con.classList.toggle('uk-hidden',false)
    }else if(type == 'kyc'){
      wallet_input.setAttribute('disabled',true)
      wallet_input.removeAttribute('required')
      wallet_con.classList.toggle('uk-hidden',true)

      local_agent.setAttribute('disabled',true)
      local_agent.removeAttribute('required')
      local_agent_con.classList.toggle('uk-hidden',true)

      medium_name.setAttribute('disabled',true)
      medium_name.removeAttribute('required')
      medium_name_con.classList.toggle('uk-hidden',true)

      medium_account_name.setAttribute('disabled',true)
      medium_account_name.removeAttribute('required')
      medium_account_name_con.classList.toggle('uk-hidden',true)

      medium_account_number.setAttribute('disabled',true)
      medium_account_number.removeAttribute('required')
      medium_account_number_con.classList.toggle('uk-hidden',true)
    }
  }
</script>
@endpush
@extends('layouts.app')
@section('title', 'Request Withdrawal')
@section('content')
<div class="uk-container uk-padding-remove">

  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">WITHDRAW NOW</h2>
          <p class="uk-margin-remove-top">
            Request a withdrawal from your account
          </p>
        </div>
        <div class="uk-card-body uk-padding-small">
          <form method="POST" autocomplete="off" action="{{route('user_create_withdraw_fund_save')}}"
            class="uk-form-stacked">
            @csrf
            <div class="uk-margin uk-width-1-1">
              <label for="withdraw_type" class="uk-form-label">
                Type <span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <select onchange="set_withdraw_type(this.value)" class="uk-select" name="withdraw_type" required
                    id="withdraw_type">
                    <option value=""> -- Select Type -- </option>
                    <option selected value="bitcoin"> To Bitcoin </option>
                    <option value="kyc"> To KYC </option>
                    <option value="local"> To Service Center </option>
                  </select>
                </div>
                @error('withdraw_type')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1">
              <label for="amount" class="uk-form-label">
                Withdrawing Amount <b>(Service Fee 5%)</b> <span class="red-text uk-text-bold">*</span>
              </label>
              <label for="amount" class="uk-form-label">
                Min ${{number_format(100,0)}} - Max
                ${{number_format((Auth()->user()->wallet * ((100-5) / 100)),2)}}
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon">$</span>
                  <input onkeyup="calc_service_fee(this.value)"
                    class="uk-input @error('amount') uk-form-danger @enderror" name="amount" id="amount" type="number"
                    max="{{(Auth()->user()->wallet * ((100-5) / 100))}}" min="100" value="{{ old('amount') }}"
                    required>
                </div>
                <span id="amount_warning" class="uk-hidden"></span>
                @error('amount')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1" id="btc_wallet_container">
              <label for="btc_address" class="uk-form-label">
                Bitcoin Wallet <span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon">&#8383;</span>
                  <input onblur="val_btc_address(this.value)"
                    class="uk-input @error('btc_address') uk-form-danger @enderror" name="btc_address" id="btc_address"
                    type="text" minlength="20" value="{{ old('btc_address') }}">
                </div>
                <span id="wallet_warning" class="uk-hidden"></span>
                @error('btc_address')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1 uk-hidden" id="local_pay_container">
              <label for="btc_address" class="uk-form-label">
                Servce Center <span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <select class="uk-select" name="agent" disabled id="agent">
                    <option value=""> -- Select Agent -- </option>
                    @foreach($agents as $agent)
                    <option value="{{$agent->username}}"> {{$agent->name}} </option>
                    @endforeach
                  </select>
                </div>
                @error('agent')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1 uk-hidden" id="medium_name_container">
              <label for="medium_name" class="uk-form-label">
                Network Name<span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon" uk-icon="name: user"></span>
                  <input
                    class="uk-input @error('medium_name') uk-form-danger @enderror" name="medium_name" id="medium_name"
                    value="{{ old('medium_name') }}" disabled>
                </div>
                <span id="amount_warning" class="uk-hidden"></span>
                @error('medium_name')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1 uk-hidden" id="medium_account_name_container">
              <label for="medium_account_name" class="uk-form-label">
                MOMO Name<span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon" uk-icon="name: user"></span>
                  <input
                    class="uk-input @error('medium_account_name') uk-form-danger @enderror" name="medium_account_name"
                    id="medium_account_name" value="{{ old('medium_account_name') }}" disabled>
                </div>
                <span id="amount_warning" class="uk-hidden"></span>
                @error('medium_account_name')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1 uk-hidden" id="medium_account_number_container">
              <label for="medium_account_number" class="uk-form-label">
                MOMO Number<span class="red-text uk-text-bold">*</span>
              </label>
              <div class="uk-form-control">
                <div class="uk-inline uk-width-1-1">
                  <span class="uk-form-icon" uk-icon="name: user"></span>
                  <input
                    class="uk-input @error('medium_account_number') uk-form-danger @enderror"
                    name="medium_account_number" id="medium_account_number" value="{{ old('medium_account_number') }}"
                    disabled>
                </div>
                <span id="amount_warning" class="uk-hidden"></span>
                @error('medium_account_number')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1">
              <div class="uk-form-control">
                <button type="submit" class="uk-button green accent-2 white-text uk-text-bolder uk-width-1-1"
                  uk-icon="icon:arrow-right;ratio:1.3">
                  Request Withdraw
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
