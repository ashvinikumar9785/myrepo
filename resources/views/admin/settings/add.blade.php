@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'setting-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Field Title</label>
                    {{Form::text('field_title',null,array('placeholder'=>'Field Title','id'=>'field_title','class'=>'form-control'))}}
                </div>
                @if($id)
                <div class="form-group">
                    <label>Field Name</label>
                    {{Form::text('field_name',null,array('placeholder'=>'Field Name','readonly'=>'readonly','class'=>'form-control'))}}
                    <span class="text-danger field_name"></span>
                </div>
                @else
                <div class="form-group">
                    <label>Field Name</label>
                    {{Form::text('field_name',null,array('placeholder'=>'Field Name','id'=>'field_name','readonly'=>'readonly','class'=>'form-control'))}}
                    <span class="text-danger field_name"></span>
                </div>
                @endif
                <div class="form-group">
                    <label>Field Type</label>
                    {{Form::select('field_type',array('text'=>'Text','image'=>'Image','email'=>'Email','number'=>'Number','url'=>'Url','date'=>'Date'),null,array('placeholder'=>'Select
                    Field Type','id'=>'field_type','class'=>'form-control'))}}
                </div>
                <div class="form-group new-field">
                    @if($id)
                    @if($data->field_type=='image')
                    <label>Field Value</label>
                    <input type="file" id="value" data-default-file="{{($data->value && file_exists($data->value))?url($data->value):''}}" name="value" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @else
                    <label>Field Value</label>
                    {!!Form::text('value',null,array('placeholder'=>'Value','id'=>'value','class'=>'form-control'))!!}
                    @endif
                    @endif
                </div>
                <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                <a class="btn btn-secondary" href="{{route('admin.settings.index')}}">Back</a> 
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$("#field_title").keyup(function(){
  var Text = $(this).val();
  Text = convertToName($.trim(Text));
  $("#field_name").val(Text);    
});
$("#field_title").change(function(){
    var Text = $(this).val();
    Text = convertToName($.trim(Text));
    $("#field_name").val(Text);    
});
$(function(){
  $('#setting-form').ajaxForm({
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
        window.location.href = '{{route('admin.settings.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
    }); 
});

$("#field_type").on('change',function(e){
    var type = e.target.value;
    if(type=="image"){
      var append = '<label>Field Value</label>{!!Form::file('value',array('id'=>'value','class'=>'form-control'))!!}<span class="text-danger value"></span>'; 
      $(".new-field").html(append);
      $("#value").dropify();     
    }else{
      var append = '<label>Field Value</label><input type='+type+' class="form-control" name="value" required="required" placeholder="Value"><span class="text-danger value"></span>';
      $(".new-field").html(append);
    } 
});
</script>
@endsection