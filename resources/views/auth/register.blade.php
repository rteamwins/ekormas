@push('scripts_bottom')
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5eb9b5c78ee2956d73a02b5d/default';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
</script>
<!--End of Tawk.to Script-->
@endpush
@extends('layouts.app')
@section('title', 'Create Your Account')
@section('content')
<div class="uk-container uk-margin-large-top">
  <div class="uk-width-1-1 uk-text-center">
    <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">Create Account</h2>
    <p class="uk-margin-remove-top">
      Create your account and start earning
    </p>
  </div>
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-1-2@m">
      <form method="POST" action="{{route('register_save')}}" class="uk-form-stacked">
        @csrf
        <div uk-grid>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="name" class="uk-form-label">
              Name *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="user"></span>
                <input class="uk-input uk-border-rounded @error('name') uk-form-danger @enderror" name="name"
                  type="text" autocomplete="name" value="{{ old('name') }}" required autofocus>
              </div>
              @error('name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="phone" class="uk-form-label">
              Phone *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="receiver"></span>
                <input class="uk-input uk-border-rounded @error('phone') uk-form-danger @enderror" name="phone"
                  type="text" minlength="8" value="{{ old('phone') }}" required>
              </div>
              @error('phone')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="referer" class="uk-form-label">Referer *</label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1"><span class="uk-form-icon" uk-icon="users"></span><input
                  class="uk-input uk-border-rounded @error('referer') uk-form-danger @enderror" name="referer"
                  type="text" readonly value="{{$referer}}"></div>@error('referer')<span
                class="uk-text-danger">{{ $message }}</span>@enderror
            </div>
          </div>

          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="placement_id" class="uk-form-label">Placement Id *</label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1"><span class="uk-form-icon" uk-icon="users"></span><input
                  class="uk-input uk-border-rounded @error('placement_id') uk-form-danger @enderror" name="placement_id"
                  type="text" readonly value="{{$placement_id}}"></div>@error('placement_id')<span
                class="uk-text-danger">{{ $message }}</span>@enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="username" class="uk-form-label">
              Username *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="bookmark"></span>
                <input class="uk-input uk-border-rounded @error('username') uk-form-danger @enderror" name="username"
                  type="text" autocomplete="username" minlength="3" value="{{ old('username') }}" required>
              </div>
              @error('username')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="email" class="uk-form-label">
              Email *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon">@</span>
                <input class="uk-input uk-border-rounded @error('email') uk-form-danger @enderror" name="email"
                  type="email" value="{{ old('email') }}" required>
              </div>
              @error('email')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="password" class="uk-form-label">
              Password *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="lock"></span>
                <input class="uk-input uk-border-rounded @error('password') uk-form-danger @enderror" name="password"
                  type="password" value="{{ old('password') }}" required>
              </div>
              @error('password')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="confirm_password" class="uk-form-label">
              Confirm Password *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="lock"></span>
                <input class="uk-input uk-border-rounded @error('confirm_password') uk-form-danger @enderror"
                  name="confirm_password" type="password" required>
              </div>
              @error('confirm_password')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-margin-top uk-align-center">
            <div class="uk-form-control">
              <input class="uk-checkbox" type="checkbox" name="terms_of_use_and_disclaimer"
                id="terms_of_use_and_disclaimer">
              <label for="terms_of_use_and_disclaimer">
                I Agree to <a href="{{route('tac')}}">Terms of use</a> & <a
                  href="{{route('disclaimer')}}">Disclaimer</a>
              </label>
            </div>
            @error('terms_of_use_and_disclaimer')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-margin-top uk-align-center">
            <div class="uk-form-control ">
              <button type="submit"
                class="uk-button uk-border-rounded green accent-2 white-text uk-text-bolder uk-width-1-1">
                <span class="uk-text-large">P</span>roceed
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
