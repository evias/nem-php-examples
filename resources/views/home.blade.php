@extends('layouts.app')
 
 @section('moduleTitle')
     <span>Dashboard</span>
 @endsection
 
@section('content')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

You are logged in!
@endsection
