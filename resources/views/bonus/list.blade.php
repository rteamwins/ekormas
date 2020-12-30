@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Bonus Recieved')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">BONUS</h2>
          <p class="uk-margin-remove-top">
            All Your Bonuses
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>AMOUNT</th>
                <th>TYPE</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($bonuses))
              @foreach ($bonuses as $bonus)
              <tr class="{{$bonus->amount<0?"red":"green"}} lighten-5">
                <td><span class="uk-hidden@m uk-text-bold">#: </span>{{$loop->index +1}}</td>
                <td><span class="uk-hidden@m uk-text-bold">Amount: </span>${{number_format($bonus->amount,2)}}</td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Type: </span> <span class="uk-label cyan">
                    {{str_replace("_"," ",$bonus->type)}}
                  </span>
                </td>
                <td><span class="uk-hidden@m uk-text-bold">Date: </span>{{ $bonus->created_at->DiffForHumans()}}</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="uk-text-center" colspan="4"> <span class="uk-label cyan"> No Data to Display</span></td>
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
