@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Local Pay Withdraw')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">LOCAL PAY WITHDRAWAL</h2>
          <p class="uk-margin-remove-top">
            All local Withdrawal made by you
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>AMOUNT</th>
                <th>FEE</th>
                <th>POP</th>
                <th>NETWORK</th>
                <th>MOMO NAME</th>
                <th>MOMO NUMBER</th>
                <th>STATUS</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($local_pays))
              @foreach ($local_pays as $local_pay)
              <tr>
                <td>
                  <span class="uk-hidden@m uk-text-bold">#: </span>
                  {{$loop->index +1}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Amount: </span>
                  ${{number_format($local_pay->amount,2)}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Fee: </span>
                  ${{number_format($local_pay->fee,2)}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">POP: </span>
                  @if($local_pay->pop !== null)
                  <a target="_blank" href="{{$local_pay->pop}}">View POP</a>
                  @else
                  NO POP
                  @endif
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Network: </span>
                  {{$local_pay->bank_name}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">MoMo Name: </span>
                  {{$local_pay->account_name}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">MoMo Number: </span>
                  {{$local_pay->account_number}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Status: </span>
                  <span class="uk-label green">
                    {{$local_pay->status}}
                  </span>
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Requested: </span>
                  {{ $local_pay->created_at->diffForHumans()}}
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="uk-text-center" colspan="9"> <span class="uk-label cyan"> No Data to Display</span></td>
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
