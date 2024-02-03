@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
                <div class="alert alert-primary">
                    <strong>How To Use:</strong>
                    <p class="m-b-0">When using these templates pass the values of these keywords in same order in order to replace the keywords.</p>
                </div>
                <div class="text-right mb-3">
                    <a href="{{route("admin.emailtemplates.add")}}" class="btn btn-secondary">Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>id</th>    
                            <th>Title</th>    
                            <th>Slug</th>    
                            <th>Keywords</th>    
                            <th>Status</th>    
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
        ajax: '{!! route('admin.emailtemplates.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'slug', name: 'slug'},
            {data: 'keywords', name: 'keywords'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });    
</script>
@endsection