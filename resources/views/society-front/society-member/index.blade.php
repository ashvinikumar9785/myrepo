@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="text-right mb-2">
                    <a href="{{route('front.society-member.add')}}" class="btn btn-secondary btn-md">Add New</a>
                </div>
                 {{Form::model(null,array('files'=>'true','class'=>'','id'=>'import-form','autocomplete'=>'on'))}}
                      <div class="form-group row">
                        <div class="col-md-6">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="import_file" id="import_file">
                                <text id="showExFile" style="position: absolute;left: 115px;z-index: 8;top: 6px;"></text>
                                <label class="custom-file-label" for="import_file">Import CSV...</label>
                                
                            </div>
                        </div>
                         <div class="col-md-3">
                             <div class="form-group">
                            <label></label>
                            <button class="btn btn-primary" id="import-btn" type="submit">Import</button>
                           
                            
                            </div>
                      
                         </div> 
                        <div class="col-md-3">
                             <div class="form-group">
                            <label></label>
                            <a href="{{url('public/uploads/csv-files/members.xlsx')}}" class="btn btn-primary">Download Sample</a> 
                            
                            </div>
                      
                         </div> 
                    </div>
                    {{Form::close()}}
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th width="30">id</th>    
                            <th>Title</th>     
                            <th width="90">Email</th>    
                            <th width="90">Mobile Number</th>    
                            <th width="50">Status</th>    
                            <th width="">Action</th>    
                        </thead> 
                    </table>
                </div>        
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
     $('#users-table').DataTable({
        processing: true,
        serverSide: true, 
        ajax: '{!! route('front.society-member.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'}, 
            {data: 'email', name: 'email'},
            {data: 'mobile_number', name: 'mobile_number'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order:[[0,'desc']]
    });
 $("#import-form").on('submit',function(e){
    e.preventDefault();
    $("#import-btn").html('<i class="fas fa-spinner fa-spin"></i>');
      disable("#import-btn",true); 

        $.ajax({
        url: '{{$ajaxUrll}}',
        type: 'POST',
          data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
         error:function(err){ 
          handleError(err);
          disable("#import-btn",false);  
        $("#import-btn").html('Import');

        },
        success: function (response) {
            if(response.status=='true'){
              $("#import-btn").html('Import');
          disable("#import-btn",false);  

                 Alert(response.message,true);
        window.location.href = '{{route('front.society-member.index')}}';

            }else{
              $("#import-btn").html('Import');
          disable("#import-btn",false);  

                Alert(response.message,false);
            }
        }

    });
    })

</script>
@endsection