@extends('layouts.app')

        @section('content')

        <a href="/posts" class="btn btn-primary">Go Back</a>

        <h1>{{$post->title}}</h1>
        <img style="width:50%" src="/storage/cover_images/{{$post->cover_image}}" alt="">
        <br>
        <br>

        <div>
            {!!$post->body!!}
        </div>
        <hr>

        <!-- if statement prevents edit and delete buttons from being seen if user isnt authenticated-->
            @if (!Auth::guest())

                <!-- if statement prevents users from deleted other users posts-->
                @if (Auth::user()->id == $post->user_id)
                    
                <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
                <hr>
                <a href="/posts/{{$post->id}}/edit" class="btn btn-primary">Edit</a>
                
                {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'float-right'])!!}

                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}

                {!!Form::close()!!}
                @endif
            @endif
        @endsection