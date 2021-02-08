@push('scripts_bottom')
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5eb9b5c78ee2956d73a02b5d/default';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
</script>
<!--End of Tawk.to Script-->
@endpush
@extends('layouts.app')
@section('title', 'Store')
@section('content')
<product-shop-list :states="{{$states}}" :lgas="{{$lgas}}"></product-shop-list>
@endsection
