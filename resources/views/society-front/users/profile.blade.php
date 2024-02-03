@extends('admin.layout.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$title}}</h4><hr> 
                {{Form::model($data,['class'=>'','files'=>true])}}
                <div class="form-group m-t-20">
                    <label class="">Name</label> 
                    {{Form::text('name',null,['class'=>'form-control name','placeholder'=>'Name'])}}
                    <span class="text-danger">{{$errors->first('name')}}</span>
                </div>
                <div class="form-group">
                    <label class="">Email</label> 
                    {{Form::email('email',null,['class'=>'form-control name','placeholder'=>'Email'])}}
                    <span class="text-danger">{{$errors->first('email')}}</span>
                </div>
                <div class="form-group">
                    <label class="">Profile</label> 
                    <input type="file" data-default-file="{{($data->profile_image && file_exists($data->profile_image))?url($data->profile_image):''}}" name="profile_image" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    <span class="text-danger">{{$errors->first('profile_image')}}</span>
                </div>
                <button type="submit" class="btn btn-secondary btn-block">Save</button>
                {{Form::close()}} 
            </div>
        </div>
    </div>
</div>

@endsection