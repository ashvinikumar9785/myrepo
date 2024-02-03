@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-primary">
                    <strong>How To Use:</strong>
                    <p class="mb-0">Enclose dynamic variable within <b>{}</b>, for example <b>{email}</b> and add these variables in keywords input box seperated with comma like <b>{email},{name}</b>.</p>
                </div>
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'submit-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Title</label>
                    {{Form::text('title',null,array('placeholder'=>'Title','id'=>'title','class'=>'form-control'))}}
                </div>
                @if($id)
                <div class="form-group">
                    <label>Slug</label>
                    {{Form::text('slug',null,array('placeholder'=>'Slug','readonly'=>'readonly','id'=>'slug','class'=>'form-control'))}}
                </div>
                @else
                <div class="form-group">
                    <label>Slug</label>
                    {{Form::text('slug',null,array('placeholder'=>'Slug','id'=>'slug','class'=>'form-control'))}}
                </div>
                @endif
                <div class="form-group">
                    <label>Keywords</label>
                    {{Form::text('keywords',null,array('placeholder'=>'Keywords','id'=>'keywords','class'=>'form-control'))}}
                </div>
                <div class="form-group">
                    <label>Content</label>
                    {{Form::textarea('content',null,array('placeholder'=>'Content','id'=>'content','class'=>'form-control'))}}
                </div> 
                <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                <a class="btn btn-secondary" href="{{route('admin.emailtemplates.index')}}">Back</a> 
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<link rel="stylesheet" href="{{url('public/admin/assets/libs/summernote/summernote-bs4.css')}}">
@endsection

@section('scripts') 
<script src="{{url('public/admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>
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
        window.location.href = '{{route('admin.emailtemplates.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
  }); 

  $('#content').summernote({
      height:300
  });
});    
</script>
@endsection