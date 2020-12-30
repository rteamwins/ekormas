@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Wallet Withdrawals')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">WITHDRAWAL</h2>
          <p class="uk-margin-remove-top">
            All Wallet Withdrawals made by you
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>AMOUNT</th>
                <th>FEE</th>
                <th>TYPE</th>
                <th>STATUS</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($withdrawals))
              @foreach ($withdrawals as $withdraw)
              <tr>
                <td>
                  <span class="uk-hidden@m uk-text-bold">#: </span>
                  {{$loop->index +1}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Amount: </span>
                  ${{number_format($withdraw->amount,2)}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Fee: </span>
                  ${{number_format($withdraw->fee,2)}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Type: </span>
                  <span class="uk-label cyan">
                    @if(class_basename($withdraw->type) == 'bitcoin')
                    Bitcoin
                    @elseif(class_basename($withdraw->type) == 'kyc')
                    KYC
                    @elseif(class_basename($withdraw->type) == 'local')
                    Local
                    @endif
                  </span>
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Status: </span>
                  <span class="uk-label green">
                    {{$withdraw->status}}
                  </span>
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Requested: </span>
                  {{ $withdraw->created_at->diffForHumans()}}
                </td>
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
