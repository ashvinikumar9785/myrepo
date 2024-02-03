@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">  
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'submit-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Name</label>
                    {{Form::text('name',null,array('placeholder'=>'Name','id'=>'name','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Email</label>
                    {{Form::email('email',null,array('placeholder'=>'Email Address','id'=>'email','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Mobile Number</label>
                    {{Form::text('mobile_number',null,array('placeholder'=>'Mobile Number','id'=>'mobile_number','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Address</label>
                    {{Form::text('address',null,array('placeholder'=>'Enter Address','id'=>'address','class'=>'form-control','rows'=>3))}}
                </div> 
                <div class="form-group">
                    <label>Pincode</label>
                    {{Form::text('pin_code',null,array('placeholder'=>'Pin Code','id'=>'pin_code','class'=>'form-control'))}}
                </div> 

                <div class="form-group">
                    <label>Owner Type</label>
                     <select name="owner_type" id="owner_type" class="select form-control">
                         <option value="">Please select</option>

                         <option value="Owner" {{(@$data->owner_type=="Owner")? 'selected':''}}>Owner </option>
                        <option value="Rent" {{(@$data->owner_type=="Rent")? 'selected':''}}>Rent</option>
                    </select>               
                   
                </div> 

               
                 
                <div class="form-group">
                <label>Password</label>
                {{Form::text('user_password',null,array('placeholder'=>'Password','id'=>'user_password','class'=>'form-control'))}}
                </div> 
                @if(!$id)
                <div class="form-group">
                <label>Confirm Password</label>
                {{Form::text('confirm_password',null,array('placeholder'=>'Confirm Password','id'=>'confirm_password','class'=>'form-control'))}}
                </div>
                @endif
                 <div class="form-group">
                    <label>Image</label>
                    @if($id)
                    <input type="file" id="image" data-default-file="{{($data->image && file_exists($data->image))?url($data->image):''}}" name="image" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @else
                    <input type="file" id="image" name="image" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @endif
                </div> 
                <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                <a class="btn btn-secondary" href="{{route('front.society-member.index')}}">Back</a> 
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
        window.location.href = '{{route('front.society-member.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
  }); 
});
</script>
@endsection
