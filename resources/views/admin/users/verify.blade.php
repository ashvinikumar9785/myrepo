@extends('admin.layout.minimal')

@section('content')
<div class="text-white text-center">
    <h3>Hi {{$data->name}}</h3>
    <p>Thank you for joining, your email has been verified successfully, you can now login to your account.</p>
</div>
@endsection