@extends('society-front.layouts.app')


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
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Phone</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->mobile_number)?$data->mobile_number:'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Blood Group</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->blood_group)?$data->blood_group:'---'}}</p>
                                </div>
                            </div>
                             <div class="row"> 
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Anniversary </strong>
                                    <br>
                                    <p class="text-muted">{{@($data->anniversary_date   )?$data->anniversary_date   :'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Service/business/Profession </strong>
                                    <br>
                                    <p class="text-muted">{{@($data->occupation)?$data->occupation:'---'}}</p>
                                </div>
                            </div>
                            <div class="row"> 
                                 <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Pincode</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->pin_code)?$data->pin_code:'---'}}</p>
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
                                 <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Pincode</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->pin_code)?$data->pin_code:'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Birthday Date</strong>
                                    <br>
                                   <p class="text-muted">{{@($data->dob   )?$data->dob   :'---'}}</p>
                                </div>
                            </div>
                            
                            <div class="row"> 
                                <div class="col-md-12 col-xs-12 b-r m-b-15"> <strong>Address  </strong>
                                    <br>
                                    <p class="text-muted">{{@($data->address)?$data->address:'---'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-success" href="{{route('front.society-member.index')}}">Go Back</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="table-responsive">
                            @if(isset($data->family_member) && count($data->family_member)>0)
                            <h3 class="text-center" style="margin-top: 5px;">Family Member</h3>
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>Name</th>     
                            <th width="">Email</th>    
                            <th width="">Mobile Number</th>    
                            <th width="">Profile Picture</th>
                        </thead> 
                        @foreach($data->family_member as $member)
                        <tr>
                            <td>{{@$member->name}}</td>
                            <td>{{@$member->email}}</td>
                            <td>{{@$member->mobile_number}}</td>

                            <td>@if($member->profile_picture)<img src="{{url(@$member->profile_picture)}}"  height="100px" width="100px"/> @endif</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif

                    @if(isset($data->vehicle_list) && count($data->vehicle_list)>0)
                            <h3 class="text-center" style="margin-top: 5px;">Vehicle List</h3>
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>Vehicle Number</th>     
                            <th width="">Type</th>    
                            <th width="">Image</th>
                        </thead>
                    @foreach($data->vehicle_list as $vehicle)
                        <tr>
                            <td>{{@$vehicle->vehicle_number}}</td>
                            <td>{{@$vehicle->type}}</td>
                            
                            <td>@if($vehicle->image)<img src="{{url(@$vehicle->image)}}"  height="100px" width="100px"/> @endif</td>
                            
                           
                        </tr>
                    @endforeach
                    </table>
                    @endif

                     @if(isset($data->visitor_list) && count($data->visitor_list)>0)
                            <h3 class="text-center" style="margin-top: 5px;">Visitor List</h3>
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>Name</th>     
                            <th width="">Mobile Number</th>    

                            <th width="">Image</th>
                        </thead>
                    @foreach($data->visitor_list as $visitor)
                        <tr>
                            <td>{{@$visitor->name}}</td>
                            <td>{{@$visitor->mobile_number}}</td>
                              <td>@if($visitor->image)<img src="{{url(@$visitor->image)}}"  height="100px" width="100px"/> @endif</td>
                        </tr>
                    @endforeach
                    </table>
                    @endif
                </div> 
                    </div>
                    <div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection