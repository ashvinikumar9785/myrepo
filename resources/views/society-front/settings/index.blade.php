@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <div class="alert alert-primary">
                    <strong>How To Use:</strong>
                    <p class="m-b-0">You can get the value of each setting anywhere on your site by calling <code>setting('field name')</code></p>
                </div>  
                <div class="text-right mb-2">
                    <a href="{{route('admin.settings.add')}}" class="btn btn-secondary btn-md">Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>id</th>
                            <th>Field Title</th>
                            <th>Field Name</th>
                            <th>Field Type</th>
                            <th>Value</th>
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
    ajax: '{!! route('admin.settings.datatables') !!}',
    columns: [
        {data: 'id', name: 'id'},
        {data: 'field_title', name: 'field_title'},
        {data: 'field_name', name: 'field_name'},
        {data: 'field_type', name: 'field_type'},
        {data: 'value', name: 'value'},
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ]
});
</script>
@endsection