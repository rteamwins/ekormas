@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Points Recieved')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">POINTS</h2>
          <p class="uk-margin-remove-top">
            All Your Points
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
              @if (count($points))
              @foreach ($points as $point)
              <tr class="{{$point->amount<0?"red":"green"}} lighten-5">
                <td><span class="uk-hidden@m uk-text-bold">#: </span>{{$loop->index +1}}</td>
                <td><span class="uk-hidden@m uk-text-bold">Amount: </span>{{number_format($point->amount,2)}}
                  @if($point->type =='daily_sales_dormant_strong_leg')
                  <span class="uk-label orange uk-text-bold">DPV</span>
                  @elseif($point->type =='daily_sales_active_weak_leg')
                  <span class="uk-label green uk-text-bold">APV</span>
                  @endif
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Type: </span> <span class="uk-label cyan">
                    {{str_replace("_"," ",$point->type)}}
                  </span>
                </td>
                <td><span class="uk-hidden@m uk-text-bold">Date: </span>{{ $point->created_at->DiffForHumans()}}</td>
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
        @if ($points->hasPages())
        <div class="uk-text-center uk-card-footer">
          {!! $points->links() !!}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection