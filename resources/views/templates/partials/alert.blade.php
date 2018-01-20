
@if(Session::has('success'))
	<div class="notification is-success">
		<button class="delete"></button>
		{{ Session::get('success') }}
	</div>
@elseif (Session::has('info'))

	<div class="notification is-info">
		<button class="delete"></button>
		{{ Session::get('success') }}
	</div>
@elseif (Session::has('warning'))
	<div class="notification is-warning">
		<button class="delete"></button>
		{{ Session::get('warning') }}
	</div>
@elseif (Session::has('danger'))
	<div class="notification is-danger">
		<button class="delete"></button>
		{!! Session::get('danger') !!}
	</div>
@endif