@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Registration Credits Purchased')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">REGISTRATION CREDIT PURCHASE</h2>
          <p class="uk-margin-remove-top">
            All Registration Credit Purchase
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>PACKAGE</th>
                <th>QUANTITY</th>
                <th>AMOUNT</th>
                <th>STATUS</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($rc_purchases))
              @foreach ($rc_purchases as $rc_purchase)
              <tr>
                <td><span class="uk-hidden@m uk-text-bold">#: </span>{{$loop->index +1}}</td>
                <td><span class="uk-hidden@m uk-text-bold">PACKAGE: </span>{{$rc_purchase->package}}</td>
                <td><span class="uk-hidden@m uk-text-bold">QUANTITY: </span>{{$rc_purchase->quantity}}</td>
                <td><span class="uk-hidden@m uk-text-bold">AMOUNT: </span>${{number_format($rc_purchase->amount,2)}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">STATUS: </span> <span class="uk-label cyan">
                    {{$rc_purchase->status}}
                  </span>
                </td>
                <td><span class="uk-hidden@m uk-text-bold">DATE: </span>{{ $rc_purchase->created_at->DiffForHumans()}}
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="uk-text-center" colspan="6"> <span class="uk-label cyan"> No Data to Display</span></td>
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
