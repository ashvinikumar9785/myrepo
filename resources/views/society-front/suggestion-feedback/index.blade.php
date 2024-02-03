@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th width="30">id</th>    
                            <th>Title</th>     
                            <th width="400">Description</th>    
                            <th width="100">Status</th>    
                            <th>Action</th>    
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
        ajax: '{!! route('front.suggestion-feedback.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'}, 
            {data: 'description', name: 'description'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order:[[0,'desc']]
    });


</script>
@endsection