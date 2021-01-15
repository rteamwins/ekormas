@push('style_top')
<style>
  .agent_logo {
    height: 40px;
    width: 40px;
    object-fit: cover;
  }
</style>
@endpush
@extends('layouts.app')
@section('title', 'Available Products')
@section('content')
<div class="uk-container uk-margin-bottom uk-margin-large-top uk-margin-large-bottom">
  <div class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-grid-match uk-grid-small uk-margin-top" uk-grid>
    @for ($i = 1; $i < 10; $i++) <div class="uk-margin-small-bottom">
      <div class="uk-card uk-card-default uk-card-body
          uk-padding-remove uk-margin-small my-card uk-link-text">
        <div class="uk-card-media-top uk-overflow-hidden">
          <a href="#" class="uk-link-reset uk-transition-toggle" tabindex="0">
            <img class="uk-img uk-width-1-1 uk-transition-scale-up uk-transition-opaque"
              src="{{asset("images/misc/service{$i}.jpeg")}}"  alt="" />
          </a>
          <div class="uk-overlay uk-card-default green white-text
                uk-position-top-left uk-position-small uk-padding-left-remove"
            style="border-radius:50px; height: 25px; padding:5px;  margin: 20px;">
            <p class="uk-text-small" style="padding:0px 6px">
              <i uk-icon="icon:star; ratio:1" style="color:white;"></i>
              Trending
            </p>
          </div>
        </div>
        <div class="uk-card-body uk-text-center uk-padding-small">
          <a href="#" class="uk-link-reset">
            <h5 class="uk-text-small green-text  text-accent-2  uk-text-bold uk-display-block uk-text-truncate">
              Product
              kur iur kiu iu8hre kiuer iuer jiperi uerjh Name</h5>
          </a>
          <div uk-grid="" class="uk-text-small uk-grid-collapse uk-grid">
            <div class="uk-width-2-3 uk-first-column">
              <a href="#" class="uk-button uk-button-default uk-padding-remove-horizontal
                  uk-border-pill green accent-2 uk-text-bold white-text
                  uk-text-truncate uk-margin-remove uk-width-1-1
                  uk-align-center">
                <i uk-icon="icon:cart"></i>
                ${{ number_format(random_int(1000,10000)) }}
              </a>
            </div>
            <div class="uk-width-1-3">
              <img src="{{asset('images/misc/zhio_fav.png')}}" alt="ad agent image"
                class="uk-border-circle agent_logo uk-align-right" />
            </div>
          </div>
        </div>
      </div>
  </div>
  @endfor
</div>
</div>

@endsection
