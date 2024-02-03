@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">{{$title}}</h4>
				{{Form::model($data,['id'=>'submit-form','files'=>true])}}
				<div class="form-group m-t-20">
					<label class="">Name</label>
					{{Form::text('name',null,['class'=>'form-control name','placeholder'=>'Name','id'=>'name'])}}
				</div>
				<div class="form-group">
					<label class="">Email</label>
					{{Form::email('email',null,['class'=>'form-control name','placeholder'=>'Email','id'=>'email'])}}
				</div>
				<div class="form-group">
					<label class="">Profile</label>
					<input type="file" id="profile_picture" data-default-file="{{($data->profile_picture && file_exists($data->profile_picture))?url($data->profile_picture):''}}" name="profile_picture" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif" data-max-file-size="3M" data-allowed-formats="square landscape"/> 
				</div>
				<button type="submit" id="submit-btn" class="btn btn-secondary btn-block"><span id="licon"></span>Save</button>
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
			}else{
				Alert(response.message,false);
			}
		}
    }); 
});
</script>
@endsection