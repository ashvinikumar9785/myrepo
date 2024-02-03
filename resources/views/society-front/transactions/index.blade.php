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
                            <th width="50">Name</th>     
                            <th>Amount</th>    
                            <th>Description</th>    
                            <th width="50">Status</th>    
                            <th width="115">Created At</th>    
                            <th width="115">Mark as Paid</th>    
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
        ajax: '{!! route('front.transactions.datatables') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'user_id', name: 'user_id'}, 
            {data: 'amount', name: 'amount'},
            {data: 'description', name: 'description'},
            {data: 'status', name: 'status'}, 
            {data: 'created_at', name: 'created_at'}, 
            {data: 'mark_as_complete', name: 'mark_as_complete'}, 
            // {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order:[[0,'desc']]
    });


</script>
@endsection