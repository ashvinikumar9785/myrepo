@extends('admin.layout.minimal')

<!-- Main Content -->
@section('content') 
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password/reset') }}">
    {{ csrf_field() }}
    <div class="row p-b-20">
        <div class="col-12">
            <div class="input-group mb-3 {{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-email"></i></span>
                </div>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email"
                    value="{{ old('email') }}" autofocus />
                @if ($errors->has('email'))
                <div class="text-danger row col-12">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row border-top border-secondary">
        <div class="col-12">
            <div class="form-group">
                <div class="p-t-20"> 
                    <button class="btn btn-success" type="submit">Send Password Reset Link</button>
                    <a class="btn btn-info float-right" href="{{url('admin/login')}}" id="to-recover"><i class="fa fa-lock m-r-5"></i>
                        Back to login?</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection