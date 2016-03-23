@extends('layouts.master')

@section('title')
	Trending Quotes
@endsection

@section('styles')
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@endsection

@section('content')

	<!-- if there is something (a name) in the first segment of the URL -->
	@if(!empty(Request::segment(1)))
		<section class="filter-bar">
		A fiter has been set!<br>
		<a href="{{ route('index') }}">Show all quotes</a>
		</section>
	@endif

	@if(count($errors) > 0)
		<section class="info-box fail">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
			</ul>
		</section>
	@endif

	@if(Session::has('success'))
		<section class="info-box success">
		{{Session::get('success')}}
		</section>
	@endif

	<section class="quotes">
		<h1>Latest Quotes</h1>
		@foreach ($quotes as $quote)
			<article class="quote">
				<div><a class="delete" href="{{ route('delete', ['quote_id' => $quote->id]) }}">x</a></div>
				{{$quote->quote}}
				<div class="info">Created by <a href="{{ route('index', ['author' => $quote->author->name]) }}">{{ $quote->author->name }}</a> on {{ date('d F, Y', strtotime($quote->created_at)) }}</div>
			</article>
		@endforeach
		<div class="pagination">
			<!-- {{ $quotes->links()}} //basic pagination -->

			<!-- if currentpage is not the first page, create a previous button -->
			@if($quotes->currentPage() !==1)
				<a href="{{ $quotes->previousPageUrl() }}"><span class="fa fa-caret-left"></span></a>
			@endif
			<!-- if currentpage is not the last page and there is more pages, create a next button -->
			@if($quotes->currentPage() !== $quotes->lastPage() && $quotes->hasPages())
				<a href="{{ $quotes->nextPageUrl() }}"><span class="fa fa-caret-right"></span></a>
			@endif

		</div>
	</section>
	<section class="edit-quote">
		<h1>Add a Quote</h1>
		<form method="post" action="{{ route('create') }}">
			<div class="input-group">
				<label for="author">Your Name:</label>
				<input type="text" name="author" id="author" placeholder="Your Name">
			</div>
			<div class="input-group">
				<label for="email">Your Email:</label>
				<input type="text" name="email" id="email" placeholder="Your Email">
			</div>
			<div class="input-group">
				<label for="author">Your Quote:</label>
				<textarea name="quote" id="quote" rows="5" placeholder="Quote"></textarea>
			</div>
			<button type="submit" class="btn">Submit Quote</button>
			<input type="hidden" name="_token" value="{{ Session::token() }}">
		</form>
	</section>
@endsection