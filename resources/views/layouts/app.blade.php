<!doctype html>
<html class="grey lighten-4" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description"
    content="{{ config('app.name', 'TGLM') }} - TGLM is a network marketing created long side the ecommerce platform, for investors, buyers and entreprenuer.">
  <meta name="title" content="{{ config('app.name', 'TGLM') }} - @yield('title')">

  <link rel="shortcut icon" href="{{asset('images/misc/favicon.jpg')}}" type="image/x-icon">
  <title>{{ config('app.name', 'TGLM') }} | @yield('title')</title>

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
    .navbar_bg {
      background: linear-gradient(to bottom, #5ab995, #3ba77d);
    }

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

    .tl {
      font-size: 2em;
      font-weight: 800;
      color: #7ed957;
    }

    .fwu {
      font-weight: bold;
      line-height: 2em;
      text-align: justify;
    }

    .why-bg {
      background-color: #1d7151;

    }

    .why-tl {
      padding-top: 50px;
      font-size: 2em;
      font-weight: 800;
      color: white;
    }

    .why-text {
      font-weight: bold;
      line-height: 2em;
      text-align: justify;
      color: white;
      opacity: 0.6;
    }

    .footer-bg {
      background-color: #131416;

    }

    .footer-about-tl {
      padding-top: 50px;
      font-size: 1.5em;
      font-weight: 800;
      color: #1d7151;
      opacity: 0.7;
    }

    .footer-tl {
      padding-top: 50px;
      font-size: 1.2em;
      font-weight: 800;
      color: white;
      opacity: 0.7;
    }

    .footer-text {
      color: white;
      opacity: 0.3;
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
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('product_store')}}" class="uk-button-small uk-text-bold uk-text-left uk-width-1-1">PRODUCT
              STORE</a>
          </div>
        </li>
        @guest
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('login')}}"
              class="uk-button-small white-text  text-accent-2 uk-text-bold uk-text-left uk-width-1-1">LOGIN</a>
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
        @if(in_array(auth()->user()->role,['user','agent','admin','buyer']))
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small cyan-text text-darken-1 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #ORDER
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('order_list')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">HISTORY</a>
              </div>
            </li>
            @if(in_array(auth()->user()->role,['admin']))
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('admin_order_request_list')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">NEW ORDERS</a>
              </div>
            </li>
            @endif
          </ul>
        </li>
        <li class="uk-parent uk-padding-remove">
          <a href="#"
            class="uk-button-small cyan-text text-darken-1 uk-text-bold uk-text-right uk-width-1-1 uk-margin-small-top uk-margin-small-bottom">
            #PROFILE
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="#" class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">VIEW</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="#" class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">EDIT</a>
              </div>
            </li>
          </ul>
        </li>
        @endif
        @if(in_array(auth()->user()->role,['user','agent','admin']))
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
            #POINTS
          </a>
          <ul class="uk-nav-sub uk-padding-remove">
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('create_point_to_wallet_funds')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">CLAIM</a>
              </div>
            </li>
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('user_point_history')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">POINT HISTORY</a>
              </div>
            </li>
            @if(in_array(auth()->user()->role,['admin']))
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('admin_list_point_nominees')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">NOMINEES</a>
              </div>
            </li>
            @endif
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
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">LOCAL WITHDRAW
                  HISTORY</a>
              </div>
            </li>
            @if(in_array(auth()->user()->role,['agent','admin']))
            <li>
              <div class="uk-padding-small green accent-2">
                <a href="{{route('local_pay_requests')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">LOCAL PAY REQUEST</a>
              </div>
            </li>
            @endif
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
                <a href="{{route('user_list_purchase_registration_credits')}}"
                  class="uk-button-small green accent-2 uk-text-bold white-text uk-width-1-1">REGISTRATION
                  CREDITS</a>
              </div>
            </li>
          </ul>
        </li>
        <li>
          <div class="uk-padding-small  @if(auth()->user()->downlines->count() <100) uk-disabled grey-text @endif">
            <a href="{{route('agent_application_form')}}" class="uk-button-small green-text uk-text-bold uk-width-1-1"
              style="">MAKE ME AN AGENT
            </a>
            <progress class="uk-progress uk-margin-remove" value="{{auth()->user()->downlines->count()}}"
              max="100"></progress>
          </div>
        </li>
        @endif
        @endauth
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('faq')}}" class="uk-button-small uk-text-bold uk-text-left uk-width-1-1">FAQ</a>
          </div>
        </li>
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('about_us')}}" class="uk-button-small uk-text-bold uk-text-left uk-width-1-1">ABOUT US</a>
          </div>
        </li>
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('disclaimer')}}"
              class="uk-button-small uk-text-bold uk-text-left uk-width-1-1">DISCLAIMER</a>
          </div>
        </li>
        <li>
          <div class="uk-padding-small green accent-2">
            <a href="{{route('tac')}}" class="uk-button-small uk-text-bold uk-text-left uk-width-1-1">TERMS OF USE</a>
          </div>
        </li>
        @auth
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
  <div class="uk-light navbar_bg" style="height: 80px" uk-sticky="bottom: #offset">
    <div class="uk-postion-top">
      <nav class="uk-navbar-container uk-padding-remove-horizontal uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-left">
          @auth
          <a class="uk-navbar-item uk-logo" href="{{route('user_home')}}"><img src="{{asset("/images/misc/logo.png")}}"
              style="height:70px;border-radius:10px;"></a>
          @else
          <a class="uk-navbar-item uk-logo" href="{{route('home')}}"><img src="{{asset("/images/misc/logo.png")}}"
              style="height:70px;border-radius:10px;"></a>
          @endauth
        </div>


        <div class="uk-navbar-right uk-visible@m ">
          <ul class="uk-navbar-nav uk-flex uk-flex-middle uk-background-primary uk-margin-small-right">
            <li class="">
              <a href="{{route('product_store')}}"
                class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important">Shop Now</a>
            </li>
            <li class="">
              <a href="{{route('about_us')}}" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important">About Us</a>
            </li>
            <li>
              <a href="{{route('faq')}}" class="uk-button uk-button-text uk-width-1-1  uk-text-bold"
                style="color:white;min-height:50px !important">FAQ</a>
            </li>
            <li>
              <a href="#contact_us" class="uk-button uk-button-text uk-width-1-1 btn-bg-none uk-text-bold"
                style="color:white;min-height:50px !important" uk-scroll>Contact Us</a>
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
        <div class="uk-navbar-right uk-margin-small-right">
          <ul class="uk-navbar-nav">
            <li>
              <button class="uk-navbar-toggle uk-button uk-button-text white-text" type="button"
                uk-toggle="target: #side_menu">
                <span>
                  <i class=" uk-display-block" uk-icon="icon: menu; ratio:1.5;"></i>
                  <span class="uk-text-small uk-text-bolder">MENU</span>
                </span>
              </button>
            </li>
          </ul>
        </div>

      </nav>
    </div>
  </div>

  @include('layouts.user_alert_card')
  <div id="app">
    @yield('content')
  </div>
  <footer>
    <div class="footer-bg uk-margin-top">
      <div class="uk-container">
        <div uk-grid>
          <div class="uk-width-1-1 uk-width-1-3@s">
            <div class="uk-margin">
              <h5 class="footer-about-tl">About</h5>
            </div>
            <p class="footer-text uk-margin-bottom">
              T.G.L.M stands for The Green Life Market. It is an ecommerce site. Just like the others,
              Alibaba, Aliexpress, Amazon etc., it’s a powerful ecommerce site that combines shopping and
              network marketing together in a platform.
            </p>
          </div>
          <div class="uk-width-1-1 uk-width-1-3@s">
            <div class="uk-margin">
              <h5 class="footer-tl">Useful Links</h5>
            </div>
            <ul class="uk-list uk-list-collapse">
              <li><a href="{{route('about_us')}}" class="uk-link-reset white-text">About</a></li>
              <li><a href="{{route('faq')}}" class="uk-link-reset white-text">FAQ</a></li>
              <li><a href="{{route('disclaimer')}}" class="uk-link-reset white-text">Disclaimer</a></li>
              <li><a href="{{route('tac')}}" class="uk-link-reset white-text">Terms of Use</a></li>
            </ul>
          </div>
          <div class="uk-width-1-1 uk-width-1-3@s" id="contact_us">
            <div class="uk-margin">
              <h5 class="footer-tl">Contact Us</h5>
            </div>

            <p>
              <span class="uk-text-bold">Calling Hours: Weekday: 8:00 - 19:00</span> during this time our physical
              service at various branch can be offered to you
              <br>
              <a href="tel:+443233223322" class="uk-link-reset uk-text-bolder">+44-323-322-3322</a>
              <br>
              <a target="_blank"
                href="mailto:support@thegreenlifemarket.shop?subject={{str_replace(' ','%20',"A Suitable Email Title")}}&body={{str_replace(' ','%20',"write your message here")}}"
                class="uk-link-reset uk-text-bolder">support@thegreenlifemarket.shop</a>
            </p>
            <div class="uk-margin-bottom">
              <a href="" class="uk-icon-button uk-margin-small-right" uk-icon="twitter" style="color: aqua;"></a>
              <a href="" class="uk-icon-button  uk-margin-small-right" uk-icon="facebook" style="color:blue"></a>
              <a href="" class="uk-icon-button" uk-icon="whatsapp" style="color:green; background-color: white;"></a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </footer>

  <script src="{{mix('/js/manifest.js')}}" defer></script>
  <script src="{{mix('/js/vendor.js')}}" defer></script>
  <script src="{{mix('/js/app.js')}}" defer></script>
  @stack('scripts_bottom')
</body>

</html>
