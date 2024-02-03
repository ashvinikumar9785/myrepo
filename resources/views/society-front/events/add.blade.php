@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">  
                {{Form::model($data,array('files'=>'true','class'=>'','id'=>'submit-form','autocomplete'=>'off'))}}
                <div class="form-group">
                    <label>Title</label>
                    {{Form::text('title',null,array('placeholder'=>'Title','id'=>'title','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Date</label>
                    {{Form::date('date',null,array('placeholder'=>'Date','id'=>'date','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Time</label>
                    {{Form::time('time',null,array('placeholder'=>'Time','id'=>'time','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Event Type</label>
                    <!-- 'Birthday'=>'Birthday','Retirement'=>'Retirement' -->
                    {{Form::select('type',array('Society Function'=>'Society Function'),null,array('placeholder'=>'Choose Type','id'=>'type','class'=>'form-control'))}}
                </div>
                <div class="form-group">
                    <label>Tithi</label>
                    {{Form::select('tithi',array('Pratipada'=>'Pratipada','Dwithiya'=>'Dwithiya','Trithiya'=>'Trithiya','Chaturthi'=>'Chaturthi','Panchami'=>'Panchami','Shasthi'=>'Shasthi','Saptami'=>'Saptami','Ashtami'=>'Ashtami','Navami'=>'Navami','Dashami'=>'Dashami','Ekadasi'=>'Ekadasi','Dwadashi'=>'Dwadashi','Thrayodashi'=>'Thrayodashi','Chaturdashi'=>'Chaturdashi','Amavasya'=>'Amavasya','Poornima'=>'Poornima'),null,array('placeholder'=>'Choose Tithi','id'=>'tithi','class'=>'form-control'))}}
                </div>
                <div class="form-group">
                    <label>Description</label>
                    {{Form::textarea('description',null,array('placeholder'=>'Enter Address','id'=>'description','class'=>'form-control','rows'=>3))}}
                </div> 
                 <div class="form-group">
                    <label>Address</label>
                    {{Form::text('address',null,array('placeholder'=>'Address','id'=>'address','class'=>'form-control'))}}
                </div>
                 <div class="form-group">
                    <label>Drive link</label>
                    {{Form::text('drive_link',null,array('placeholder'=>'Drive Link','id'=>'drive_link','class'=>'form-control'))}}
                </div> 
                <div class="form-group">
                    <label>Banner</label>
                    @if($id)
                    <input type="file" id="banner" data-default-file="{{($data->banner && file_exists($data->banner))?url($data->banner):''}}" name="banner" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @else
                    <input type="file" id="banner" name="banner" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif"/>
                    @endif
                    <p style="color: gray;">Banner size should be in 300X144</p>
                </div> 
                
                 <div class="form-group">
                    <label>Image</label>
                   
                    <input type="file" id="image" name="image[]" class="dropify" data-height="150" data-show-remove="false" data-allowed-file-extensions="png jpeg jpg gif" multiple />
                </div> 
                <button class="btn btn-primary" id="submit-btn" type="submit"><span id="licon"></span>Save</button> 
                <a class="btn btn-secondary" href="{{route('front.events.index')}}">Back</a> 
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
        window.location.href = '{{route('front.events.index')}}';
      }else{
        Alert(response.message,false);
      }
    }
  }); 
});
</script>
@endsection
