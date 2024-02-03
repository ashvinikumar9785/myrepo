@extends('admin.layout.app')

@section('content')

<div class="row"> 
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$title}}</h4><hr> 
                {{Form::model(null,['class'=>''])}}
                <div class="form-group m-t-20">
                    <label class="">Current Password</label> 
                    {{Form::password('current_password',['class'=>'form-control name','placeholder'=>'Current Password'])}}
                    <span class="text-danger">{{$errors->first('current_password')}}</span>
                </div>
                <div class="form-group">
                    <label class="">New Password</label> 
                    {{Form::password('new_password',['class'=>'form-control name','placeholder'=>'New Password'])}}
                    <span class="text-danger">{{$errors->first('new_password')}}</span>
                </div>
                <div class="form-group">
                    <label class="">Confirm New Password</label> 
                    {{Form::password('confirm_password',['class'=>'form-control name','placeholder'=>'Confirm Password'])}}
                    <span class="text-danger">{{$errors->first('confirm_password')}}</span>
                </div>
                <button type="submit" class="btn btn-secondary btn-block">Change Password</button>
                {{Form::close()}} 
            </div>
        </div>
    </div>
</div>

@endsection