@extends('admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$title}}</h4>
                <hr>
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'submit-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Version</label>
                    {{Form::text('version',null,array('placeholder'=>'App version','id'=>'version','class'=>'form-control'))}}
                </div>


                <div class="form-group">
                    <label>Force Stop</label>
                   
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="radio" name="force_update" {{($data->force_update == 1)?'checked':''}} class="custom-control-input" id="force_update1" value="1">
                        <label class="custom-control-label" for="force_update1">Yes</label>
                    </div>
                     <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="radio" name="force_update" class="custom-control-input" id="force_update" value="0" {{($data->force_update == 0)?'checked':''}}>
                        <label class="custom-control-label" for="force_update">No</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Maintenance</label>
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="radio" name="is_maintenance" class="custom-control-input" {{(@$data->is_maintenance == 1)?'checked':''}} id="is_maintenance1" value="1">
                        <label class="custom-control-label" for="is_maintenance1">Yes</label>
                    </div>
                     <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="radio" name="is_maintenance" class="custom-control-input" id="is_maintenance"  value="0" {{($data->is_maintenance == 0)?'checked':''}}>
                        <label class="custom-control-label" for="is_maintenance">No</label>
                    </div>
                </div>
                  <div class="form-group">
                    <label>Maintainance Message</label>
                    {{Form::text('is_maintenance_message',null,array('placeholder'=>'Message','id'=>'is_maintenance_message','class'=>'form-control'))}}
                </div>
                  <div class="form-group">
                    <label>Force Update Message</label>
                    {{Form::text('force_update_message',null,array('placeholder'=>'Message','id'=>'force_update_message','class'=>'form-control'))}}
                </div>


                <div class="form-group">
                    <label>App Store Url</label>
                    {{Form::text('app_store_url',null,array('placeholder'=>'App Store','id'=>'app_store_url','class'=>'form-control'))}}
                </div>    

                <div class="form-group">
                    <label>Play Store Url</label>
                    {{Form::text('play_store_url',null,array('placeholder'=>'Play Store','id'=>'play_store_url','class'=>'form-control'))}}
                </div>                 
  
                <button class="btn btn-primary" id="submit-btn" type="submit">Save</button> 
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
        location.reload();
      }else{
        Alert(response.message,false);
      }
    }
  }); 

  $('#content').summernote({
      height:300
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