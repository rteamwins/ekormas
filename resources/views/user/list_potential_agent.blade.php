@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Potential Agents')
@section('content')
<div class="uk-container uk-padding-remove uk-margin-bottom">

  <div class="uk-margin-large-bottom" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-default uk-padding-remove uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">POTENTIAL AGENTS</h2>
          <p class="uk-margin-remove-top">
            All Potential Agents
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
                <th>REFERALS</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody>
              @if (count($agents))
              @foreach ($agents as $agent)
              <tr>
                <td>
                  <span class="uk-hidden@m uk-text-bold">#: </span>
                  {{$loop->index +1}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Name: </span>
                  {{$agent->name}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Username: </span>
                  {{$agent->username}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Email: </span>
                  {{$agent->email}}
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Phone: </span>
                  {{$agent->phone}}
                </td>

                <td>
                  <span class="uk-hidden@m uk-text-bold">Referals: </span>
                  <span class="uk-label green">
                    {{$agent->downlines_count}}
                  </span>
                </td>
                <td>
                  <span class="uk-hidden@m uk-text-bold">Date: </span>
                  {{ $agent->created_at->diffForHumans()}}
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="uk-text-center" colspan="7"> <span class="uk-label cyan"> No Data to Display</span></td>
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
