@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$title}}</h4>
                {{Form::model(null,['id'=>'submit-form'])}}
                <div class="form-group m-t-20">
                    <label class="">Current Password</label>
                    {{Form::password('current_password',['class'=>'form-control','id'=>'current_password','placeholder'=>'Current Password'])}}
                </div>
                <div class="form-group">
                    <label class="">New Password</label>
                    {{Form::password('new_password',['class'=>'form-control','placeholder'=>'New Password','id'=>'new_password'])}} 
                </div>
                <div class="form-group">
                    <label class="">Confirm New Password</label>
                    {{Form::password('confirm_password',['class'=>'form-control','placeholder'=>'Confirm Password','id'=>'confirm_password'])}} 
                </div>
                <button type="submit" id="submit-btn" class="btn btn-secondary btn-block"><span id="licon"></span>@lang("Change Password")</button>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
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
                Alert(response.message);
                window.location.href = '{{route("admin.home")}}';
            }else{
                Alert(response.message,false);
            }
        }
    }); 
});
</script>
@endsection