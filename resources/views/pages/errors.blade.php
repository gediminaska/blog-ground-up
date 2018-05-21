@extends('layouts.app')

@section('title', ' | Test Errors')

@section('content')

    <h1 class="title">List of test failures</h1>
    <hr>

    @foreach(collect(scandir("/var/www/public/images/errors/screenshots")) as $item)
        @if(substr($item, 0, 1) !== '.' )
            <h2 class="title is-6">{{ $item }}</h2>
            <img src={{ asset("images/errors/screenshots/$item") }} alt="">
            <hr>
            {{  $errorsCaught = ' ' }}
        @endif

    @endforeach
    @if( !isset($errorsCaught))
        <h1>There are no errors!</h1>
    @endif
@endsection