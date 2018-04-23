@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/users">Users Management</a>
 <a href="/users/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Showing User {{ $user->email }}</h1>
 
    <div class="jumbotron text-center">
        <p>
            <strong>Name:</strong> {{ $user->name }}<br>
            <strong>Email:</strong> {{ $user->email }}
        </p>
    </div>
@endsection