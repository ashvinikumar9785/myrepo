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
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Title</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->title)?$data->title:''}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Tithi</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->tithi)?$data->tithi:'---'}}</p>
                                </div>
                            </div>  
                            <div class="row"> 
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Date</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->date)?$data->date:'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Time</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->time)?$data->time:'---'}}</p>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Type</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->type)?$data->type:'---'}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Drive Link</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->drive_link)?$data->drive_link:'---'}}</p>
                                </div>
                            </div>
                            
                            <div class="row"> 
                                <div class="col-md-12 col-xs-12 b-r m-b-15"> <strong>Description  </strong>
                                    <br>
                                    <p class="text-muted">{{@($data->description)?$data->description:'---'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-success" href="{{route('front.events.index')}}">Go Back</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="table-responsive">
                            @if($data->image && count($data->image)>0)
                            <h3 class="text-center" style="margin-top: 5px;">Event Image</h3>
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>Id</th>     
                            <th width="">Image</th>    
                            <th>Action</th>
                            <!-- <th>Gender</th>     -->
                        </thead> 
                        @foreach($data->image as $image)
                        <tr>
                            <td>{{$image->id}}</td>
                           
                            <td><img src="{{url($image->image)}}"  height="100px" width="100px" /></td>
                            <td><a href="{{route('front.events.image.delete',$image->id)}}">Delete</a></td>
                            <!-- <td>{{@$member->gender}}</td> -->
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