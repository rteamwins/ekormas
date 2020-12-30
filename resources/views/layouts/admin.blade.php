<!doctype html>
<html class="grey lighten-4" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description"
    content="{{ config('app.name', 'Ekormas') }} - Frieght, Truck and Shipping Service for packages, with Express Tracking and range of parcel delivery service">
  <meta name="title" content="{{ config('app.name', 'Ekormas') }} - @yield('title')">

  <title>{{ config('app.name', 'Ekormas') }} | @yield('title')</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @stack('style_top')
  <script>
    window.Laravel = @php echo json_encode([
      'csrfToken'=> csrf_token(),
    ]) @endphp;
    @if(!auth()->guest())
      window.Laravel.userId = "{{auth()->user()->id}}";
    @endif
  </script>
  <style>
    .scrolling_news {
      color: white;
      white-space: nowrap;
      overflow: hidden;
      box-sizing: border-box;
      padding: 10px;
    }

    .scrolling_news p {
      display: inline-block;
      padding-left: 100%;
      animation: scrolling_news 60s linear infinite;
    }

    @keyframes scrolling_news {
      0% {
        transform: translate(0, 0);
      }

      100% {
        transform: translate(-100%, 0);
      }
    }
  </style>
  <style>
    .admin_sidebar {
      width: 15em;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
    }

    .admin_sidebar>uk-nav {
      height: 100%;
    }

    .admin_sidebar>uk-nav:last-child {
      margin-top: auto;
    }

    .admin_maincontent {
      width: calc(100vw -15em);
      margin-left: 15em;
    }
  </style>
  @stack('style_top')
  @stack('scripts_top')
</head>

<body>
  <aside>
    <!----offcanvas start here---->
    <aside id="side_menu" class="admin_sidebar sidenav white uk-padding-remove">

      <!-----usefull links ends here---->
      <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('user_home')}}" class="uk-button-small uk-text-bold uk-text-left uk-width-1-1">HOME</a>
          </div>
        </li>
        @guest
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('login')}}"
              class="uk-button-small  white green-text  text-accent-2 uk-text-bold uk-text-left uk-width-1-1">LOGIN</a>
          </div>
        </li>
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('register')}}"
              class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-left uk-width-1-1">REGISTER</a>
          </div>
        </li>
        @endguest
        @auth
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #WALLET
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_fund_wallet')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">FUND NOW</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_fund_history')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">FUNDING HISTORY</a>
              </div>
            </li>
          </ul>
        </li>
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #TRADES
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_create_trade')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">TRADE NOW</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_trade_history')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">TRADE HISTORY</a>
              </div>
            </li>
          </ul>
        </li>
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #BONUS
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('create_bonus_to_wallet_funds')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">MOVE TO WALLET</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_bonus_history')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">BONUS HISTORY</a>
              </div>
            </li>
          </ul>
        </li>
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #WITHDRAWS
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_create_withdraw_fund')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">WITHDRAW NOW</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_withdraw_fund_history')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">WITHDRAW HISTORY</a>
              </div>
            </li>
          </ul>
        </li>
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #CREDITS
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_list_kyc')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">KYC</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="#" class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">REGISTRATION
                  CREDITS</a>
              </div>
            </li>
          </ul>
        </li>
        <li class=" uk-margin-auto-top">
          <div class="uk-padding-small red white-text">
            <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
              class="uk-button-small  uk-text-bold uk-width-1-1" style="">LOG
              OUT
            </a>
          </div>
        </li>
        @endauth
      </ul>
    </aside>
    <main class="admin_maincontent">
      <!-----session message start here----->
      @if (Session::get('error') || Session::get('success') || Session::get('info') || Session::get('warning'))
      <div class="uk-container uk-margin-medium-top">
        <div class=" uk-margin-bottom" uk-grid>
          <div class="uk-width-1-1 uk-flex uk-flex-around">
            @if ($message = Session::get('error'))
            <div
              class="uk-width-1-1 uk-width-2-3@s uk-width-3-5@m red-text text-lighten-1 red lighten-4 uk-border-rounded"
              uk-alert>
              <a class="uk-alert-close grey-text" uk-close></a>
              <h4 class="uk-h4 uk-text-bold">Error</h4>
              <p>{{$message}}</p>
            </div>
            @elseif ($message = Session::get('success'))
            <div
              class="uk-width-1-1 uk-width-2-3@s uk-width-3-5@m green-text text-lighten-1 green lighten-4 uk-border-rounded"
              uk-alert>
              <a class="uk-alert-close grey-text" uk-close></a>
              <h4 class="uk-h4 uk-text-bold">Success</h4>
              <p>{{$message}}</p>
            </div>
            @elseif ($message = Session::get('warning'))
            <div
              class="uk-width-1-1 uk-width-2-3@s uk-width-3-5@m yellow-text text-darken-2 yellow lighten-4 uk-border-rounded"
              uk-alert>
              <a class="uk-alert-close grey-text" uk-close></a>
              <h4 class="uk-h4 uk-text-bold">Notice</h4>
              <p>{{$message}}</p>
            </div>
            @elseif ($message = Session::get('info'))
            <div
              class="uk-width-1-1 uk-width-2-3@s uk-width-3-5@m cyan-text text-lighten-1 cyan lighten-4 uk-border-rounded"
              uk-alert>
              <a class="uk-alert-close grey-text" uk-close></a>
              <h4 class="uk-h4 uk-text-bold">Info</h4>
              <p>{{$message}}</p>
            </div>
            @endif
          </div>
        </div>
      </div>
      <!-----session message ends here----->
      @endif
      <div class="uk-container-fluid">
        <div class=" uk-margin-bottom" uk-grid>
          <div class="uk-width-1-1 uk-flex uk-flex-around">
            <div class="uk-width-1-1 red-text text-lighten-1 red lighten-4 uk-border-rounded" uk-alert>
              <a class="uk-alert-close grey-text" uk-close></a>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis iusto debitis minima voluptas sit
                cumque, facere molestiae fugit expedita, obcaecati incidunt provident ducimus esse iste, voluptatum sed
                a
                nam voluptate.</p>
            </div>
          </div>
        </div>
      </div>
      <div id="app">
        @yield('content')
      </div>
    </main>

    <script src="{{mix('/js/manifest.js')}}" defer></script>
    <script src="{{mix('/js/vendor.js')}}" defer></script>
    <script src="{{mix('/js/app.js')}}" defer></script>
    @stack('scripts_bottom')
</body>

</html>
