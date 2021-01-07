<div class="uk-grid-small" uk-grid>
  <div class="uk-width-1-1">
    <div class="uk-card uk-card-default uk-card-body" style="border-radius:0 0 5px 5px;padding:5px;">
      <div>
        <div class="scrolling_news green accent-2 uk-margin-remove-vertical uk-border-rounded" uk-alert>
          <p class=" uk-text-bold">
            @foreach (Auth()->user()->alerts() as $alert)
            {{ $alert->message }} <----||---->
            @endforeach
          </p>
        </div>
        @if ($message = Session::get('user-success'))
        <div class="uk-alert-success uk-margin-remove-bottom uk-margin-small-top uk-border-rounded" uk-alert>
          <a class="uk-alert-close" uk-close></a>
          <p>{!!$message!!}</p>
        </div>
        @elseif ($message = Session::get('user-info'))
        <div class="uk-alert-warning uk-margin-remove-bottom uk-margin-small-top uk-border-rounded" uk-alert>
          <a class="uk-alert-close" uk-close></a>
          <p>{!!$message!!}</p>
        </div>
        @elseif ($message = Session::get('user-error'))
        <div class="uk-alert-danger uk-margin-remove-bottom uk-margin-small-top uk-border-rounded" uk-alert>
          <a class="uk-alert-close" uk-close></a>
          <p>{!!$message!!}</p>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>
