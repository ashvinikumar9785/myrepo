@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="text-right mb-2">
                    <a href="{{route('admin.categories.add')}}" class="btn btn-secondary btn-md">Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th width="30">id</th>    
                            <th>Title</th>     
                            <th width="90">Image</th>    
                            <th width="50">Status</th>    
                            <th width="115">Action</th>    
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
        ajax: '{!! route('admin.categories.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'}, 
            {data: 'image', name: 'image'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order:[[0,'desc']]
    });


</script>
@endsection