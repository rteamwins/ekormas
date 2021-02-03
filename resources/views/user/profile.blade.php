@push('style_top')
<style>
</style>
@endpush
@extends('layouts.app')
@section('title', 'All Alert')
@section('content')
<user-profile :states="{{$states}}" :lgas="{{$lgas}}"></user-profile>
@endsection
