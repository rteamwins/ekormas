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
  @stack('style_top')
  @stack('scripts_top')
</head>

<body>
  <!----offcanvas start here---->
  <div id="side_menu" uk-offcanvas="mode: slide; overlay: true">
    <div class="uk-offcanvas-bar sidenav white uk-padding-remove">

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
        {{-- <li>
          <div class="uk-padding-small green accent-2">
            <a href="#"
              class="uk-button-small green-text  text-accent-2 uk-text-bold uk-text-left uk-width-1-1">REGISTER</a>
          </div>
        </li> --}}
        @endguest
        @auth
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small cyan-text text-darken-1 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
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
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_withdraw_local_history')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">LOCAL WITHDRAW HISTORY</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('local_pay_requests')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">LOCAL PAY REQUEST</a>
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
        <li>
          <div class="uk-padding-small red white-text">
            <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
              class="uk-button-small  uk-text-bold uk-width-1-1" style="">LOG
              OUT
            </a>
          </div>
        </li>
        @endauth
      </ul>

    </div>
  </div>
  <!----offcanvas ended here---->

  <!----navbar start here ---->
  {{-- @if(Route::currentRouteName() !== 'home')
  <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1"
    uk-slideshow="animation: push;autoplay:true;autoplay-interval:5000;min-height: 350;">
    <ul class="uk-slideshow-items">
      <li>
        <div class="uk-position-cover" uk-slideshow-parallax="scale: 1.2,1.2,1">
          <img src="{{ asset('images/misc/slider1.png') }}" alt="" uk-cover>
  </div>
  <div class="uk-position-cover" uk-slideshow-parallax="opacity: 0,0,0.2; backgroundColor: #000,#000"></div>
  <div class="uk-position-center uk-position-medium uk-text-center">
    <div uk-slideshow-parallax="scale: 1,1,0.8">
      <h3 class="uk-text-bolder" uk-slideshow-parallax="x: 200,0,0">FAST & SAFE TRANSPORTATION</h2>
        <p class="uk-visible@m" uk-slideshow-parallax="x: 400,0,0;">Zhio Logistics with sea freight
          forwarding and contract logistics, <br> taking advantage of a network spanning more than 64 locations
          in 43 countries </p>
    </div>
  </div>
  </li>
  <li>
    <div class="uk-position-cover" uk-slideshow-parallax="scale: 1.2,1.2,1">
      <img src="{{ asset('images/misc/slider2.jpg') }}" alt="" uk-cover>
    </div>
    <div class="uk-position-cover" uk-slideshow-parallax="opacity: 0,0,0.2; backgroundColor: #000,#000"></div>
    <div class="uk-position-center uk-position-medium uk-text-center">
      <div uk-slideshow-parallax="scale: 1,1,0.8">
        <h3 class="uk-text-bolder" uk-slideshow-parallax="x: 200,0,0">AMAZING TRUCKING SERVICE</h2>
          <p class="uk-visible@m" uk-slideshow-parallax="x: 400,0,0;">Our team has extensive experience in cargo
            freight, <br>imports and exports and established contacts with airlines and agents who specialize in
            air freight.</p>
      </div>
    </div>
  </li>
  <li>
    <div class="uk-position-cover" uk-slideshow-parallax="scale: 1.2,1.2,1">
      <img src="{{ asset('images/misc/slider3.jpg') }}" alt="" uk-cover>
    </div>
    <div class="uk-position-cover" uk-slideshow-parallax="opacity: 0,0,0.2; backgroundColor: #000,#000"></div>
    <div class="uk-position-center uk-position-medium uk-text-center">
      <div uk-slideshow-parallax="scale: 1,1,0.8">
        <h3 class="uk-text-bolder" uk-slideshow-parallax="x: 200,0,0">WORLDWIDE FREIGHT FORWARD</h2>
          <p class="uk-visible@m" uk-slideshow-parallax="x: 400,0,0;">We can assist customers with short and long
            term storage nationally in all our office worldwide, <br>Whether you are looking for storage space.
          </p>
      </div>
    </div>
  </li>
  </ul>
  <div class="uk-position-top">
    <nav class="uk-navbar-container uk-padding-remove-horizontal uk-navbar-transparent" uk-navbar>
      <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo " href="{{route('home')}}"><img src="{{asset("/images/misc/zhio_banner.png")}}"
            style="height:70px; border-radius:10px;"></a>
      </div>


      <div class="uk-navbar-right uk-visible@m ">
        <ul class="uk-navbar-nav uk-flex uk-flex-middle orange darken-3 uk-margin-small-right">
          <li class="">
            <a href="#" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
              style="color:white;min-height:50px !important">Tracking</a>
          </li>
          <li>
            <a href="#" class="uk-button uk-button-text uk-width-1-1  uk-text-bold"
              style="color:white;min-height:50px !important">Service</a>
          </li>
          <li>
            <a href="#" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
              style="color:white;min-height:50px !important">Contact Us</a>
          </li>
          @guest
          <li>
            <a href="{{route('login')}}" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
              style="color:white;min-height:50px !important" uk-icon="sign-in">Login</a>
          </li>
          <li>
            <a href="#" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
              style="color:white;min-height:50px !important" uk-icon="link">Register</a>
          </li>
          @endguest
          @auth
          <li>
            <a href="#" class="uk-button uk-button-text uk-width-1-1  uk-text-bold"
              style="color:white;min-height:50px !important">Office</a>
          </li>
          <a title="logout" class="uk-button uk-border-pill white-text" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
            style="color:white">
            Logout <span uk-icon="icon: lock; ratio:1.2;"></span>
          </a>
          <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
          @endauth
        </ul>
      </div>
      <div class="uk-navbar-right uk-margin-small-right uk-hidden@m">
        <ul class="uk-navbar-nav">
          <li>
            <button class="uk-navbar-toggle uk-button uk-button-text white-text" type="button"
              uk-toggle="target: #side_menu">
              <span>
                <i class=" uk-display-block" uk-icon="icon: menu; ratio:1.5;"></i>
                <span class="uk-text-small">MENU</span>
              </span>
            </button>
          </li>
        </ul>
      </div>

    </nav>
    <!----navbar ends here ---->
  </div>
  </div>
  @else --}}
  <div class=" uk-background-cover uk-light" data-src="{{ asset("images/misc/slider1.png") }}" uk-img
    style="height: 80px">
    <div class="uk-postion-top">
      <nav class="uk-navbar-container uk-padding-remove-horizontal uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-left">
          @auth
          <a class="uk-navbar-item uk-logo" href="{{route('user_home')}}"><img
              src="{{asset("/images/misc/zhio_banner.png")}}" style="height:70px;border-radius:10px;"></a>
          @else
          <a class="uk-navbar-item uk-logo" href="{{route('home')}}"><img
              src="{{asset("/images/misc/zhio_banner.png")}}" style="height:70px;border-radius:10px;"></a>
          @endauth
        </div>


        <div class="uk-navbar-right uk-visible@m ">
          <ul class="uk-navbar-nav uk-flex uk-flex-middle green accent-2 uk-margin-small-right">
            <li class="">
              <a href="{{route('user_create_trade')}}"
                class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important">Trade Now</a>
            </li>
            <li>
              <a href="{{route('user_fund_wallet')}}" class="uk-button uk-button-text uk-width-1-1  uk-text-bold"
                style="color:white;min-height:50px !important">Fund Wallet</a>
            </li>
            <li>
              <a href="#" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important">Contact Us</a>
            </li>
            @guest
            <li>
              <a href="{{route('login')}}" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important">Login</a>
            </li>
            {{-- <li>
              <a href="#" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important">Register</a>
            </li> --}}
            @endguest
            @auth
            <a title="logout" class="uk-button uk-border-pill white-text" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
              style="color:white">
              Logout <span uk-icon="icon: lock; ratio:1.2;"></span>
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
            @endauth
          </ul>
        </div>
        <div class="uk-navbar-right uk-margin-small-right uk-hidden@m">
          <ul class="uk-navbar-nav">
            <li>
              <button class="uk-navbar-toggle uk-button uk-button-text white-text" type="button"
                uk-toggle="target: #side_menu">
                <span>
                  <i class=" uk-display-block" uk-icon="icon: menu; ratio:1.5;"></i>
                  <span class="uk-text-small">MENU</span>
                </span>
              </button>
            </li>
          </ul>
        </div>

      </nav>
    </div>
  </div>
  {{-- @endif --}}
  <!-----session message start here----->
  @if (Session::get('error') || Session::get('success') || Session::get('info') || Session::get('warning'))
  <div class="uk-container uk-margin-medium-top">
    <div class=" uk-margin-bottom" uk-grid>
      <div class="uk-width-1-1 uk-flex uk-flex-around">
        @if ($message = Session::get('error'))
        <div class="uk-width-1-1 uk-width-2-3@s uk-width-3-5@m red-text text-lighten-1 red lighten-4 uk-border-rounded"
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
  <div id="app">
    <main>
      @yield('content')
    </main>
  </div>
  {{-- <footer id="footer" class="grey darken-2">
    <div class="uk-container uk-margin-small-bottom">
      <div class=" uk-margin-top uk-grid-divider" uk-grid>
        <div class="uk-width-1-3@m uk-width-1-1">
          <a class="uk-logo" href="{{route('home')}}"><img src="{{asset("/images/misc/zhio_banner.png")}}"
    style="height: 100px; border-radius:10px; "></a>
  <p class="white-text">
    <span class=" uk-margin-small-right" uk-icon="icon:location;"></span>72B ShengT Avenue, Shanghai China
  </p>
  </div>
  <div class="uk-width-1-3@m uk-width-1-1">
    <h4 class="white-text uk-text-bolder">ABOUT</h4>
    <ul class="uk-list uk-list-hyphen white-text uk-margin-remove-left">
      <li><a class="uk-link-reset" href="#">Contact Us</a></li>
      <li><a class="uk-link-reset" href="#">Our Services</a></li>
      <li><a class="uk-link-reset" href="#">Careers at Zhio Logistics</a></li>
    </ul>
  </div>
  <div class="uk-width-1-3@m uk-width-1-1">
    <form>
      <h4 class="white-text uk-text-bolder">NEWSLETTER</h4>
      <div class="uk-margin-top ">
        <div class="uk-inline">
          <button style="border: none; " class="uk-form-icon green accent-2 uk-form-icon-flip remove-highlight"
            type="submit"><i class="white-text" uk-icon="icon:forward"></i></button>
          <input class="uk-input" type="email" placeholder="Email Address">
        </div>
      </div>
      <li style="color: white; font-size:0.8em"> Get recent updates from us...</li>
    </form>
  </div>
  </div>
  </div>
  <div class="uk-text-center grey darken-3 uk-padding-small" style="color: white;">
    <p class=" uk-margin-remove-vertical"> ZhioCourier &reg; 2020. All rights reserved.</p>
    <p class="uk-text-small uk-margin-remove-vertical">Please note: All images, texts, and videos on
      this site are properties of ZhioCourier.com</p>
  </div>

  </footer> --}}

  <script src="{{mix('/js/manifest.js')}}" defer></script>
  <script src="{{mix('/js/vendor.js')}}" defer></script>
  <script src="{{mix('/js/app.js')}}" defer></script>
  @stack('scripts_bottom')
</body>

</html>
