@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Wallet Topups')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">TOP UPs</h2>
          <p class="uk-margin-remove-top">
            All Wallet top up made by you
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>AMOUNT</th>
                <th>SOURCE</th>
                <th>STATUS</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($fundings))
              @foreach ($fundings as $fund)
              <tr>
                <td>{{$loop->index +1}}</td>
                <td>${{number_format($fund->amount,2)}}</td>
                <td><span class="uk-label cyan">
                    @if(class_basename($fund->method_type) == 'CryptoTransaction')
                    Bitcoin
                    @elseif(class_basename($fund->method_type) == 'BonusTransaction')
                    Bonus
                    @else
                    KYC
                    @endif
                  </span></td>
                <td><span class="uk-label green">
                    {{$fund->status}}
                  </span></td>
                <td>{{ $fund->created_at->diffForHumans()}}</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="uk-text-center" colspan="5"> <span class="uk-label cyan"> No Data to Display</span></td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
