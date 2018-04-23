@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/users">Users Management</a>
 <a href="/users/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>User Edition Form</h1>
    <hr>
    @if ($mode == 'update')
     <form action="{{url('users', [$user->id])}}" method="POST">
    @else
     <form action="/users" method="post">
    @endif

     {{ csrf_field() }}
     <div class="form-group">
        <label for="title">Name</label>
        <input type="text" class="form-control" id="userName"  name="name" value="{{ isset($user) ? $user->name : Request::old('name') }}">
      </div>
      <div class="form-group">
        <label for="title">Email Address</label>
        <input type="text" class="form-control" id="emailAddress"  name="email" value="{{ isset($user) ? $user->email : Request::old('email') }}">
      </div>
      <div class="form-group">
        <label for="description">Password</label>
        <input type="password" class="form-control" id="userCreds" name="password">
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection