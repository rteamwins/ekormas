@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Non Active Users')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">NON ACTIVE USERS</h2>
          <p class="uk-margin-remove-top">
            All Non Active Users
          </p>
        </div>
        <div class="uk-card-body uk-padding-remove">
          <table class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider">
            <thead>
              <tr>
                <th>#</th>
                <th>NAME</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>PHONE</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($users))
              @foreach ($users as $user)
              <tr>
                <td>
                  <span class="uk-hidden@m uk-text-bold">#: </span>
                  {{$loop->index +1}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Name: </span>
                  {{$user->name}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Username: </span>
                  {{$user->username}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Email: </span>
                  {{$user->email}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Phone: </span>
                  {{$user->phone}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Date: </span>
                  {{ $user->created_at->diffForHumans()}}
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
