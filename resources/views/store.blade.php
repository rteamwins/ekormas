@extends('layouts.app')
@section('title', 'Store')
@section('content')
<product-shop-list :states="{{$states}}" :lgas="{{$lgas}}"></product-shop-list>
@endsection
