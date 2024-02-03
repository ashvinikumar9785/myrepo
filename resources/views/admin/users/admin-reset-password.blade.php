@extends('admin.layout.minimal')

@section('content')
@if($user)
<form class="form-horizontal" role="form" method="POST" action="{{ route('admin.password.setnew',$token) }}">
    {{ csrf_field() }}
 
    <div class="row p-b-30">
        <div class="col-12">
            <div class="input-group mb-3 {{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-lock"></i></span>
                </div>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password"
                    aria-label="Password" />
                @if ($errors->has('password'))
                <div class="text-danger row col-12">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-lock"></i></span>
                </div>
                <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm Password"
                    aria-label="Password" />
                @if ($errors->has('password_confirmation'))
                <div class="text-danger row col-12">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row border-top border-secondary">
        <div class="col-12">
            <div class="form-group">
                <div class="p-t-20"> 
                    <button class="btn btn-success float-right" type="submit">Reset Password</button>
                </div>
            </div>
        </div>
    </div>
</form>
@else 
<div class="text-white text-center"> 
    <p>Your password has been change successfully, you can now login with your new credentials.</p>
</div> 
@endif
@endsection