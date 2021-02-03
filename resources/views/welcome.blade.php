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

<!-- ========== Top slide show start here ========== -->
<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" tabindex="-1"
  uk-slideshow=" animation: push">

  <ul class="uk-slideshow-items">
    <li>
      <img src="{{asset('images/misc/slider1.jpeg')}}" alt="Homeslider Image 1">
    </li>
    <li>
      <img src="{{asset('images/misc/slider2.jpeg')}}" alt="Homeslider Image 2">
    </li>
    <li>
      <img src="{{asset('images/misc/slider3.jpeg')}}" alt="Homeslider Image 3">
    </li>
  </ul>

  <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous
    uk-slideshow-item="previous"></a>
  <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next
    uk-slideshow-item="next"></a>

</div>
<!-- ========== Top slide show start here ========== -->

<!--========= ============================ -->
<div class="uk-container">
  <h3 class="tl">Who We Are</h3>
  <p class="fwu">
    T.G.L.M stands for The Green Life Market. It is an ecommerce site. Just like the
    others, Alibaba, Aliexpress, Amazon etc., it’s a powerful ecommerce site that combines shopping and network
    marketing together in a platform. It is owned by a Cape Verde Company with their head office in Nossa Sen
    Hora do Monte in Brava, Cape Verde. It was launched in November 2020 and it has been a platform that has
    being tested and trusted by many customers, with new millionaire emerging every week. </p>

  <a href="{{route('about_us')}}" class="uk-button uk-button-secondary uk-button-large uk-margin"><b>Know More</b></a>
</div>
<!--========= ============================ -->

<!--========= ============================ -->

<div class="uk-margin why-bg ">
  <div class="uk-container">
    <div uk-grid>
      <div class="uk-width-1-1 uk-width-1-2@s">
        <div class="uk-margin">
          <h3 class="why-tl uk-text-center">Why join T.G.L.M </h3>
        </div>
        <p class="why-text uk-margin-bottom">T.G.L.M pays you interest on your membership package weekly, 3%
          of your membership
          is paid to you and
          3.5% on the membership package Emerald and Diamond. A 1% of your trading capital is paid to you
          weekly too. This is a wonderful opportunity that cannot be missed. A platform with more than two
          decades sustainability plan.</p>
        <img src="{{asset('images/misc/unnamed (1).png')}}" style="height: 50px; width: 50px;">
      </div>
      <div class="uk-width-1-1 uk-width-1-2@s">
        <div class="uk-margin">
          <h3 class="why-tl uk-text-center">How to join T.G.L.M</h3>
        </div>
        <p class="why-text uk-margin-bottom">To join T.G.L.M you will have to register under someone i.e.,
          you would have to be
          referred by someone to the system. The person who refers you is called your UP-LINER. A
          non-fundable
          registration fee of $10 is paid for registration and then you choose a membership package.</p>
      </div>
    </div>

  </div>
</div>
<!--========= ============================ -->

<!-- ================== product slider start here ================ -->
<homepage-product-shop-list :states="{{$states}}" :lgas="{{$lgas}}"></homepage-product-shop-list>
@endsection
