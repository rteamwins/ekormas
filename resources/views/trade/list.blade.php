@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Trades')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">TRADES</h2>
          <p class="uk-margin-remove-top">
            All trade sessions made by you
          </p>
        </div>

        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>AMOUNT</th>
                <th>PROFIT</th>
                <th>SOURCE</th>
                <th>STATUS</th>
                <th>STARTED</th>
                <th>ENDS</th>
              </tr>
            </thead>
            <tbody>
              @if (count($trades))
              @foreach ($trades as $trade)
              <tr>
                <td><span class="uk-hidden@m uk-text-bold">#: </span>{{$loop->index +1}}</td>
                <td><span class="uk-hidden@m uk-text-bold">Amount: </span>${{number_format($trade->amount,2)}}</td>
                <td><span class="uk-hidden@m uk-text-bold">Earning: </span>${{number_format($trade->earning,2)}}</td>
                <td><span class="uk-hidden@m uk-text-bold">Method: </span><span
                    class="uk-label {{$trade->method !== 'manual'?"green":"cyan"}}">{{$trade->method}}
                  </span></td>
                <td><span class="uk-hidden@m uk-text-bold">Status: </span><span
                    class="uk-label {{$trade->completed?"green":"orange"}}">
                    {{$trade->completed?"Completed":"In Session"}}
                  </span></td>
                <td><span class="uk-hidden@m uk-text-bold">Opened: </span>{{$trade->created_at->diffForHumans()}}</td>
                <td><span class="uk-hidden@m uk-text-bold">Closing: </span>{{$trade->closing_at->diffForHumans()}}</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="uk-text-center" colspan="7">No Data to Display</td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
        @if ($trades->hasPages())
        <div class="uk-text-center uk-card-footer">
          {!! $trades->links() !!}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
