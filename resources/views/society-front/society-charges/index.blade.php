@extends('society-front.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
               <div class="text-right mb-2">
                    <a href="{{route('society.society-charges.add')}}" class="btn btn-secondary btn-md">Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dtable" id="users-table">
                        <thead>
                            <th>Id</th>    
                            <th>Title</th>
                            <th>Amount</th>    
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
        ajax: '{!! route('society.society-charges.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'amount', name: 'amount'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
</script>
@endsection