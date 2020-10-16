<div class="uk-grid-small" uk-grid>
  <div class="uk-width-1-1">
    <div class="uk-card uk-card-default uk-card-body uk-padding-small" style="border-radius:0 0 5px 5px">
      <div class="uk-grid-small uk-grid-divider uk-flex-center" uk-grid>
        <div class="uk-width-1-1 uk-width-2-5@m">
          <div class=" uk-grid-collapse" uk-grid>
            <div class="uk-width-1-3 uk-flex uk-flex-wrap-middle uk-align-center uk-animation-toggle" tabindex="0">
              <img class="uk-animation-stroke uk-animation-reverse uk-border-pill" width="100" height="100"
                src="{{asset(Auth()->user()->avatar?sprintf("images/users/%s/%s",Auth()->user()->username,Auth()->user()->avatar):"images/misc/default_avatar.svg")}}"
                alt="{{Auth()->user()->avatar?"user profile picture":"Default User profile Picture"}}"
                uk-svg="stroke-animation: true">
            </div>
            <div class="uk-width-2-3">
              <ul class="uk-list uk-margin-remove-top uk-text-small uk-width-1-1">
                <li style="margin-top:5px;" class=""><span
                    class="uk-label uk-text-lowercase blue uk-text-bold uk-margin-small-right">{{Auth()->user()->email}}</span>
                </li>
                <li style="margin-top:5px;" class=""> <span
                    class="uk-label green uk-text-bold uk-text-capitalize ">Level: <span
                      class=" uk-margin-small-left">1</span>
                  </span></li>
                <li style="margin-top:5px;" class=""><span
                    class="uk-label yellow darken-3 uk-text-bold uk-text-capitalize">Next
                    Level:<span class=" uk-margin-small-left">45%</span>
                  </span></li>
                <li style="margin-top:5px;" class=""><span class="uk-label indigo uk-text-bold uk-text-capitalize">Role:
                    <span class=" uk-margin-small-left">{{Auth()->user()->role}}</span>
                  </span></li>
              </ul>
            </div>
          </div>



        </div>
        <div class=" uk-width-expand">
          <div class="uk-grid uk-grid-small">
            <div class="uk-width-1-2 uk-width-1-4@s">
              <p class="uk-text-center uk-margin-remove-bottom"> <span
                  class="uk-text-bold uk-text-large orange-text text-darken-2">WALLET</span></br>
                <span
                  class="uk-text-bold uk-text-small uk-text-large@m grey-text text-darken-2 uk-margin-remove-top">Total:
                  ${{number_format(Auth()->user()->wallet?:0,2)}}</span>
                <br>
                <span
                  class="uk-text-bold uk-text-small uk-text-large@m grey-text text-darken-2 uk-margin-remove-top">Avail:
                  ${{number_format(Auth()->user()->available_wallet?:0,2)}}</span>
                <div class="uk-text-center">
                  <a href="{{route('user_fund_wallet')}}" class="uk-icon-button uk-text-bold green lighten-1 white-text"
                    uk-icon='pull'></a>
                  <button class="uk-icon-button uk-text-bold blue lighten-1 white-text" uk-icon='push'></button>
                  <a href="{{route('user_fund_history')}}" class="uk-icon-button uk-text-bold orange white-text"
                    uk-icon='list'></a>
                </div>
              </p>
            </div>
            <div class="uk-width-1-2 uk-width-1-4@s">
              <p class="uk-text-center  uk-margin-remove-bottom"> <span
                  class="uk-text-bold uk-text-large orange-text text-darken-2">T-CAP</span></br>
                <span
                  class="uk-text-bold uk-text-small uk-text-large@m grey-text text-darken-2 uk-margin-remove-top">Total:
                  ${{number_format(Auth()->user()->trading_capital?:0,2)}}</span>
                <br>
                <span class="uk-text-bold uk-text-small uk-text-large@m text-darken-2 uk-margin-remove-top grey-text">
                  ROI: ${{number_format(Auth()->user()->trading_capital?:0,2)}}</span>
                <div class="uk-text-center">
                  <a href="{{route('user_create_trade')}}"
                    class="uk-icon-button uk-text-bold green lighten-1 white-text" uk-icon='cart'></a>
                  <a href="{{route('user_trade_history')}}" class="uk-icon-button uk-text-bold orange white-text"
                    uk-icon='list'></a>
                </div>
              </p>
            </div>
            <div class="uk-width-1-2 uk-width-1-4@s">
              <p class="uk-text-center  uk-margin-remove-bottom"> <span
                  class="uk-text-bold uk-text-large orange-text text-darken-2 ">BONUS</span></br>
                <span
                  class="uk-text-bold uk-text-small uk-text-large@m grey-text text-darken-2 uk-margin-remove-top">${{number_format(Auth()->user()->bonus?:0,2)}}</span>
              </p>
              <div class="uk-text-center">
                <button class="uk-icon-button uk-text-bold blue lighten-1 white-text" uk-icon='cart'></button>
                <button class="uk-icon-button uk-text-bold orange white-text" uk-icon='list'></button>
              </div>
            </div>
            <div class="uk-width-1-2 uk-width-1-4@s">
              <p class="uk-text-center  uk-margin-remove-bottom"> <span
                  class="uk-text-bold uk-text-large orange-text text-darken-2">R-CREDIT</span></br>
                <span
                  class="uk-text-bold uk-text-small uk-text-large@m grey-text text-darken-2 uk-margin-remove-top">Avail:
                  33</span>
                <br>
                <span
                  class="uk-text-bold uk-text-small uk-text-large@m grey-text text-darken-2 uk-margin-remove-top">Downlines:
                  42</span>
                <div class="uk-text-center">
                  <button class="uk-icon-button uk-text-bold blue white-text" uk-icon='users'></button>
                  <button class="uk-icon-button uk-text-bold orange white-text" uk-icon='list'></button>
                </div>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div>
        @if ($message = Session::get('user-success'))
        <div class="uk-alert-success uk-margin-remove-vertical uk-border-rounded" uk-alert>
          <a class="uk-alert-close" uk-close></a>
          <p>{!!$message!!}</p>
        </div>
        @elseif ($message = Session::get('user-info'))
        <div class="uk-alert-warning uk-margin-remove-vertical uk-border-rounded" uk-alert>
          <a class="uk-alert-close" uk-close></a>
          <p>{!!$message!!}</p>
        </div>
        @elseif ($message = Session::get('user-error'))
        <div class="uk-alert-danger uk-margin-remove-vertical uk-border-rounded" uk-alert>
          <a class="uk-alert-close" uk-close></a>
          <p>{!!$message!!}</p>
        </div>
        @endif
        <div class="scrolling_news blue lighten-3 uk-margin-remove-vertical uk-border-rounded" uk-alert>
          <p class=" black-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, fugit debitis ut minima cum ducimus
            delectus autem veritatis qui ratione tenetur porro iure optio sit quasi aspernatur suscipit odio tempore. >>>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
