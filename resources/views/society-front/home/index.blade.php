@extends('society-front.layouts.app')
@section('content')
<div class="row">
	<div class="col-md-6 col-lg-4">
		<a href="{{route('front.society-member.index')}}">
			<div class="card card-hover">
				<div class="box bg-cyan text-center">
					<h1 class="font-light text-white">{{number_format($users_count)}}</h1>
					<h6 class="text-white">Users</h6>
				</div>
			</div></a>
		</div>
		<!-- <div class="col-md-6 col-lg-4">
			<a href="{{route('admin.categories.index')}}">
				<div class="card card-hover">
					<div class="box bg-cyan text-center">
						<h1 class="font-light text-white">{{number_format($categories_count)}}</h1>
						<h6 class="text-white">Categories</h6>
					</div>
				</div>
			</a>
		</div> -->
	</div>
	@endsection