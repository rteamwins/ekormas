@push('style_top')
<style>
  .full-height {
    height: 75vh;
  }

  .flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
  }

  .position-ref {
    position: relative;
  }

  .content {
    text-align: center;
  }

  .title {
    font-size: 1.5rem;
    padding: 20px;
    color: #636b6f;
  }

  .code {
    font-size: 3rem;
    font-weight: 600;
    padding: 0 20px;
  }

  .link {
    font-size: 1rem;
    text-align: center;
    text-transform: uppercase;
    text-decoration: none;
    padding: 0.5em;
    border: #636b6f 1px solid;
    background-color: transparent;
    color: #636b6f;
    margin: 1em;
  }

  .link:hover {
    background-color: #636b6f;
    color: #fff;
  }
</style>
@endpush
@extends('layouts.app')
@section('title', "$".number_format($amount,2)." Wallet Funding Payment Failed")
@section('content')

<div class="flex-center position-ref full-height">
  <div class="content">
    <div class="code">
      <img src="{{asset('images/misc/cash_payment_failed.svg')}}" alt="">
    </div>
    <div class="title">
      <p>Wallet Funding: <b>${{number_format($amount,2)}}</b> <br> Payment Error</p>
      <p>The transaction was: <br> <span class="uk-text-bold red-text">CANCELLED / FAILED</span></p>
    </div>
    <div>
      <a class="uk-button uk-background-primary white-text uk-text-bolder" href="{{ route('user_home') }}">
        <span uk-icon="arrow-left"></span> Home
      </a>
      <a class="uk-button uk-background-primary white-text uk-text-bolder" href="{{ route('user_fund_history') }}">
        <span uk-icon="list"></span> History
      </a>
    </div>
  </div>
</div>
@endsection
