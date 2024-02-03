@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <div class="col-lg-12">
                    <div class="ui raised segment">
                        
                        <h3 class="text-center" style="margin-top: 5px;">Feedback/Suggestion Detail</h3>
                        <hr>
                        <div style="text-align:center">
                            <div class="row">
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Title</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->title)?$data->title:''}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Feedback By</strong>
                                    <br>
                                    <a href="{{route('front.society-member.view',$data->user['id'])}}">{{@($data->user['name'])?$data->user['name']:'---'}}</a>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Status</strong>
                                    <br>
                                    <p class="text-muted">{{$data->status}}</p>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r m-b-15"> <strong>Description</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->description)?$data->description:'---'}}</p>
                                </div>
                            </div>  
                            
                            <div class="row"> 
                                <div class="col-md-12 col-xs-12 b-r m-b-15"> <strong>Remark</strong>
                                    <br>
                                    <p class="text-muted">{{@($data->comment)?$data->comment:'---'}}</p>
                                </div>
                               
                            </div>
                           @if($data->image)
                           @foreach($data->image as $image)
                           <img src="{{url($image->image)}}"  />
                           @endforeach
                           @endif
                             
                        </div>
                       
                        </div>
                        <div class="card">
            <div class="card-body"> 
                         <div class="row"> 
                            <div class="col-md-12 col-xs-12 b-r m-b-15"> 
                                <form id="changeStatus" class="form-group" method="post">
                                     <div class="form-group">
                                        <label>Select Status</label>
                                        <select id="feedBackStatus" class="select form-control">
                                            <option value="">Please select</option>
 
                                          <!--   <option value="IN WORK">IN WORK</option>
                                            <option value="IN QUEUE">IN QUEUE</option>
                                            <option value="RESOLVED">RESOLVED</option>
                                            <option value="CLOSE">CLOSE</option> -->
                                             <option value="IN PROGRESS">IN PROGRESS </option>
                                            <option value="RESOLVED">RESOLVED</option>
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                    <label>Comment</label>
                                    <textarea id="comment" class="form-control" name="comment" >{{@$data->comment}}</textarea>
                                </div>  
                                    <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                            </form>
                              
                            </div>
                        </div>

</div></div>                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(function(){
    var frm = $('#changeStatus');
     frm.submit(function (ev) {
        var status = $("#feedBackStatus").val();
        var comment = $("#comment").val();
        $.ajax({
        url: '{{$ajaxUrll}}',
        type: 'POST',
        data:{status:status,comment:comment},
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        
        success: function (response) {
            if(response.status=='true'){
                 Alert(response.message,true);
            }else{
                Alert(response.message,false);
            }
        }
    });
                ev.preventDefault();

    })
  $('#submit-form').ajaxForm({
    beforeSubmit:function(){
      $(".error").remove();
      disable("#submit-btn",true); 
    },
    error:function(err){ 
      handleError(err);
      disable("#submit-btn",false);  
    },
    success:function(response){ 
      disable("#submit-btn",false); 
      if(response.status=='true'){
        window.location.href = '{{route('front.news-updates.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
  }); 
});
</script>
@endsection
