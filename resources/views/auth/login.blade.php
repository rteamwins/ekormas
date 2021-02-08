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
@section('title', 'Login Now')
@section('content')
    <div class="uk-section uk-section-small uk-section-muted uk-flex uk-flex-center">
        <div class="uk-card uk-card-default uk-card-body uk-width-large">
            <h2 class="uk-card-title">Log In</h2>
            <form method="POST" action="{{ route('login') }}" class="uk-form-stacked">
                @csrf
                <div class="uk-margin">
                    <label for="email" class="uk-form-label">
                        {{ __('E-Mail Address') }}
                    </label>
                    <div class="uk-form-control">
                        <input class="uk-input @error('email') uk-form-danger @enderror" name="email" id="email" type="email"
                               value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="uk-text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="uk-margin">
                    <label for="password" class="uk-form-label">
                        {{ __('Password') }}
                    </label>
                    <div class="uk-form-control">
                        <input id="password" type="password"
                               class="uk-input @error('password') uk-form-danger @enderror" name="password" required
                               autocomplete="current-password">
                        @error('password')
                        <span class="uk-text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-control">
                        <input class="uk-checkbox" type="checkbox" name="remember"
                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-control">
                        <button type="submit" class="uk-button uk-button-primary">
                            {{ __('Log In') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="uk-button uk-button-link uk-margin-left" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
