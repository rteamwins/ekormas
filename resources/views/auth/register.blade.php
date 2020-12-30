{{-- @push('scripts_bottom')
<script>
</script>
@endpush --}}
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
      <form method="POST" action="{{route('register')}}" class="uk-form-stacked">
        @csrf
        <div uk-grid>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="first_name" class="uk-form-label">
              First Name *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="user"></span>
                <input class="uk-input uk-border-rounded @error('first_name') uk-form-danger @enderror"
                  name="first_name" type="text" autocomplete="given-name" value="{{ old('first_name') }}" required autofocus>
              </div>
              @error('first_name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-width-1-1 uk-width-1-2@s uk-margin-small-top">
            <label for="last_name" class="uk-form-label">
              Last Name *
            </label>
            <div class="uk-form-control uk-width-1-1">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="user"></span>
                <input class="uk-input uk-border-rounded @error('last_name') uk-form-danger @enderror" autocomplete="family-name" name="last_name"
                  type="text" value="{{ old('last_name') }}" required>
              </div>
              @error('last_name')
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
                  type="text" value="{{ old('referer') }}"></div>@error('referer')<span
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
