@extends('society-front.layouts.minimal')
@section('content')
	<div id="loginform">
	<form autocomplete="off" class="form-horizontal m-t-20" id="loginform" method="post" action="{{route('society.login')}}">
		{{csrf_field()}}
		<div class="row p-b-30">
			<div class="col-12">
				<div class="mb-3"> 
					<input autocomplete="off" type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1" required="">
				</div>
				<div class="mb-3"> 
					<input name="password" id="password" type="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required="">
				</div>
			</div>
		</div>
		<div class="row border-top border-secondary">
			<div class="col-12">
				<div class="form-group">
					<div class="p-t-20">
						<button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Lost password?</button>
						<button id="submit-btn" class="btn btn-success float-right" type="submit"><span id="licon"></span>Login</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	</div>
<div id="recoverform">
	<div class="text-center">
		<span class="text-white">Enter your e-mail address below and we will send you instructions how to recover a password.</span>
	</div>
	<div class="row m-t-20">
		<!-- Form -->
		<form class="col-12" id="submit-form" action="{{route('admin.forgotpassword')}}" method="post">
			{{csrf_field()}}
			<!-- email -->
			<div class="mb-3"> 
				<input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email Address" aria-label="Username" aria-describedby="basic-addon1"> 
			</div>
			<!-- pwd -->
			<div class="row m-t-20 p-t-20 border-top border-secondary">
				<div class="col-12">
					<a class="btn btn-success" href="javascript:void(0)" id="to-login" name="action">Back To Login</a>
					<button id="submit-btn" class="btn btn-info float-right" type="submit"><span id="licon"></span> Recover</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() { 
	$('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-login').click(function(){ 
        $("#recoverform").hide();
        $("#loginform").fadeIn();
    });

    // bind 'myForm' and provide a simple callback function 
    $('#loginform').ajaxForm({
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
    			window.location.href = response.url;
    		}else{
    			disable("#submit-btn",false); 
    			Alert(response.message,false);
    		}
    	}
    });

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
    			Alert(response.message,true);
    			$("#email").val(" ");
    		}else{
    			Alert(response.message,false);
    		}
    	}
    }); 
}); 
</script>	
@endsection