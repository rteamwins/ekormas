@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Withdrawals')
@section('content')
<div class="uk-container uk-margin-large-top">
  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">WITHDRAWALS</h2>
      <p class="uk-margin-remove-top">
        All Withdrawals made by you
      </p>
      <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
        <thead>
          <tr>
            <th>#</th>
            <th>AMOUNT</th>
            <th>FEE</th>
            <th>SOURCE</th>
            <th>STATUS</th>
            <th>DATE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1.</td>
            <td>$232,323.5434</td>
            <td>$34.3493</td>
            <td><span class="uk-label cyan">WALLET
              </span></td>
            <td><span class="uk-label green">
                COMPLETED
              </span></td>
            <td>{{now()}}</td>
          </tr>
          <tr>
            <td class="uk-text-center" colspan="6">No Data to Display</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
