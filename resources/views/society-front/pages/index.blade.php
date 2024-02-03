@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="text-right mb-3">
                    <a href="{{route("society.pages.add")}}" class="btn btn-secondary">Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>id</th>    
                            <th>Page Title</th>    
                            <th>Page Slug</th>    
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
        ajax: '{!! route('society.pages.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'slug', name: 'slug'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
</script>
@endsection