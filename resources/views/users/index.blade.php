@extends('layouts.app')
 
@section('moduleTitle')
    <a href="/users">Users Management</a>
    <a href="/users/create">
        <button type="button" class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i>&nbsp;Create</button>
    </a>
@endsection

@section('content')
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Email address</th>
            <th scope="col">Name</th>
            <th scope="col">Created At</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <th scope="row">{{$user->id}}</th>
            <td><a href="/users/{{$user->id}}">{{$user->email}}</a></td>
            <td>{{$user->name ?: "N/A"}}</td>
            <td>{{$user->created_at->toFormattedDateString()}}</td>
            <td>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <div class="col-md-6">
                        <a href="{{ URL::to('users/' . $user->id . '/edit') }}">
                            <button type="button" class="btn btn-sm btn-primary">
                                <i class="fa fa-edit"></i>&nbsp;Edit</button>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <!--
                    <form action="{{url('users', [$user->id])}}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-sm btn-danger" value="Delete"/>
                    </form>
                        -->
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection