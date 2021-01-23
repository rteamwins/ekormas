@push('style_top')
<style>
  .agent_logo {
    height: 40px;
    width: 40px;
    object-fit: cover;
  }
</style>
@endpush
@extends('layouts.app')
@section('title', 'Available Products')
@section('content')

<product-shop-list :states="{{$states}}" :lgas="{{$lgas}}"></product-shop-list>
@endsection
