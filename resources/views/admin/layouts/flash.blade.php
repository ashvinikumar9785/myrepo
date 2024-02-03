@if(session()->has('success') or session()->has('warning') or session()->has('info') or session()->has('danger') or
session()->has('primary') or session()->has('secondary'))
<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info','primary','secondary'] as $msg)
	@if(session()->has($msg))
	<div class="alert absolte_alert  alert-{{ $msg }}">{{ session()->get($msg)}} <a href="javascript:void(0)" class="close"
	data-dismiss="alert" aria-label="close">&times;</a></div>
	@endif
	@endforeach
	</div> <!-- end .flash-message -->
	@endif