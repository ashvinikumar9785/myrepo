@extends('admin.layouts.minimal')
@section('content') 
<div id="loginform">
<form autocomplete="off" class="form-horizontal m-t-20" id="loginform" method="post" action="{{route('users.resetpassword',$token)}}">
	{{csrf_field()}}
	<div class="row p-b-30">
		<div class="col-12">
			<div class="mb-3"> 
				<input name="password" id="password" type="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" >
			</div>
			<div class="mb-3"> 
				<input name="password_confirmation" id="password_confirmation" type="password" class="form-control form-control-lg" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon1" >
			</div>
		</div>
	</div>
	<div class="row border-top border-secondary">
		<div class="col-12">
			<div class="form-group">
				<div class="p-t-20">
					<a href="{{route('admin.login')}}"><button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Back to Login</button></a>
					<button id="submit-btn" class="btn btn-success float-right" type="submit"><span id="licon"></span>Update Password</button>
				</div>
			</div>
		</div>
	</div>
</form>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() { 
    // bind 'myForm' and provide a simple callback function 
    $('#loginform').ajaxForm({
        resetForm:true,
    	beforeSubmit:function(){
    		$(".error").remove();
    		disable("#submit-btn",true); 
    	},
    	error:function(err){ 
    		handleError(err);
    		disable("#submit-btn",false); 
    	},
    	success:function(response){  
    		if(response.status=='true'){
    			Alert(response.message);
                $("#loginform").html('<p style="color: #fff;text-align: center;font-size: 16px">'+response.message+'</p>');
    		}else{ 
    			Alert(response.message,false);
    		}
            disable("#submit-btn",false); 
    	}
    }); 
}); 
</script>	
@endsection