@extends ('layouts.master')

@section('content')
	<style>
	.input-group label {
		text-align: left;
	}
	</style>
	@if(count($errors) > 0)
		<section class="info-box fail">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
			</ul>
		</section>
	@endif
	@if(Session::has('fail'))
		<section class="info-box fail">
			{{ Session::get('fail') }}
		</section>
	@endif
	<form action="{{ route('admin.login') }}" method="post">
		<div class="input-group">
			<label for="name">Your Name:</label>
			<input type="text" name="name" id="name" placeholder="Your Name">
		</div>
		<div class="input-group">
			<label for="password">Your Password:</label>
			<input type="password" name="password" id="password" placeholder="Your Password">
		</div>
		<button type="sumit">Submit</button>
		<input type="hidden" name="_token" value="{{ Session::token() }}">
	</form>
@endsection