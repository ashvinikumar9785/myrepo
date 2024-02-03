@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive"> 
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>Id</th>    
                            <th>Name</th>    
                            <th>Email</th>    
                            <th>Status</th>    
                            <th>Created at</th>    
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
        ajax: '{!! route('admin.users.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'status', name: 'status'}, 
            {data: 'created_at', name: 'created_at'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order:[[0,'desc']]
    });
</script>
@endsection