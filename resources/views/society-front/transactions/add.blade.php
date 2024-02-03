@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">  
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'submit-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Title</label>
                    {{Form::text('title',null,array('placeholder'=>'Name','id'=>'title','class'=>'form-control'))}}
                </div> 
               
                <div class="form-group">
                    <label>Description</label>
                    {{Form::textarea('description',null,array('placeholder'=>'Enter Description','id'=>'description','class'=>'form-control','rows'=>3))}}
                </div> 

                <div class="form-group">
                    <label>Image</label>
                    @if($id)
                    <input type="file" id="image" data-default-file="{{($data->image && file_exists($data->image))?url($data->image):''}}" name="image" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @else
                    <input type="file" id="image" name="image" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @endif
                </div> 
                <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                <a class="btn btn-secondary" href="{{route('admin.society-owner.index')}}">Back</a> 
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
        window.location.href = '{{route('front.news-updates.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
  }); 
});
</script>
@endsection
