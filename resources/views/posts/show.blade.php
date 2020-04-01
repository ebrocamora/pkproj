@extends('layouts.app')

@section('content')
	<a href="/posts" class="btn btn-default">Go Back</a>
	<h1>{{$posts->title}}</h1>
	<div class="row">
		<div class="col-md-12">
			<img style="width: 100%" src="/storage/cover_image/{{$posts->cover_image}}" alt="">
		</div>
	</div>
	<p>{{$posts->body}}</p>
	<hr>
	<small>Written on {{$posts->created_at}}</small>
	<hr>
	@if(!Auth::guest())
		@if(Auth::user()->id == $posts->user_id)
			<a href="/posts/{{$posts->id}}/edit" class="btn btn-default">Edit</a>
			{!!Form::open(['action'=>['PostsController@destroy', $posts->id], 'method'=>'POST', 'class'=> 'pull-right'])!!}
			{{Form::hidden('_method', 'DELETE')}}
			{{Form::submit('Delete',['class'=> 'btn btn-danger'])}}
			{!!Form::close()!!}
		@endif
	@endif
@endsection