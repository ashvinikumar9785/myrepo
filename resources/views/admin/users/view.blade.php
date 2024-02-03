@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <div class="col-lg-12">
                    <div class="ui raised segment">
                        <div class="avatar text-center">
                            @if($data->profile_picture!="" && file_exists($data->profile_picture))
                            <img src="{{url($data->profile_picture)}}" alt="" class="img-thumbnail" style="max-height:150px;">
                            @endif
                        </div>
                        <h3 class="text-center" style="margin-top: 5px;">{{@($data->name)?$data->name:''}}</h3>
                        <hr>
                        <div style="text-align:center">
                            <div class="row">
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Full Name</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->name)?$data->name:''}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Email</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->email)?$data->email:'---'}}</p>
                                </div>
                            </div>  
                            <div class="row"> 
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>User Type</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->user_type)?$data->user_type:'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Social Type</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->social_type)?$data->social_type:'---'}}</p>
                                </div>
                            </div>
                            <div class="row"> 
                                 <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Social Id</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->social_id)?$data->social_id:'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Status</strong>
                                    <br>
                                    @if($data->status==1)
                                    <span class="text-success btn-sm">Active</span>
                                    @else
                                    <span class="text-danger btn-sm">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-success" href="{{route('admin.users.index')}}">Go Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection