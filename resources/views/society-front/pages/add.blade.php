@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'submit-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Page Title</label>
                    {{Form::text('title',null,array('placeholder'=>'Page Title','id'=>'title','class'=>'form-control'))}}
                </div>
                <div class="form-group">
                    <label>Page Slug</label>
                    {{Form::text('slug',null,array('placeholder'=>'Page Slug','id'=>'slug','readonly'=>'readonly','class'=>'form-control'))}}
                </div>
                <div class="form-group">
                    <label>Page Content</label>
                    {{Form::textarea('content',null,array('placeholder'=>'Page Content','id'=>'content','class'=>'form-control'))}}
                </div> 
               <!--  <h4 class="card-title">SEO (Optional)</h4>
                <div class="form-group">
                    <label>Meta Title</label>
                    {{Form::text('meta_title',null,array('placeholder'=>'Page Meta Title','id'=>'meta_title','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Page Meta Description</label>
                    {{Form::textarea('meta_description',null,array('placeholder'=>'Page Meta Description','id'=>'meta_description','class'=>'form-control'))}}
                </div> -->
                <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                <a class="btn btn-secondary" href="{{route('society.pages.index')}}">Back</a> 
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
<link rel="stylesheet" href="{{url('public/front/assets/libs/summernote/summernote-bs4.css')}}">
@endsection
@section('scripts')
<script src="{{url('public/front/assets/libs/summernote/summernote-bs4.min.js')}}"></script>
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
        window.location.href = '{{route('society.pages.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
  }); 

  $('#content').summernote({
      height:300,
      
  });
});

$("#title").keyup(function(){
    var Text = $(this).val();
    Text = convertToName($.trim(Text));
    $("#slug").val(Text);    
});
$("#title").change(function(){
    var Text = $(this).val();
    Text = convertToName($.trim(Text));
    $("#slug").val(Text);    
});
</script>
@endsection