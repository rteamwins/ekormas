@push('style_top')
<style>
  .ref_card {
    width: 200px;
  }
</style>
@endpush
@push('bottom_scripts')
<script>
  // let ref_top = document.getElementById('ref_top')
  // let ref_bottom = document.getElementById('ref_bottom')
  // ref_top.clientWidth = ref_bottom.clientWidth
</script>
@endpush
@extends('layouts.app')
@section('title', 'All Referals')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">DOWNLINES</h2>
          <p class="uk-margin-remove-top">
            All Downlines invited by you
          </p>
        </div>
        <div class="uk-card-body" style="padding:5px;">
          <referal-slider></referal-slider>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
